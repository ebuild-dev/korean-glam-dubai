<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;
use Throwable;

class ImportCatalogue extends Command
{
    protected $signature = 'catalogue:import {directory : Dossier contenant catalogue.xlsx et images.json} {--dry-run : Valider sans modifier la base ni copier les images}';

    protected $description = 'Importer un catalogue complet dans des tables vides avec sauvegarde SQL et correspondance des IDs';

    private const TABLES = [
        'Marques' => 'brands',
        'Catégories' => 'categories',
        'Sous-catégories' => 'souscategories',
        'Sous-sous-catégories' => 'sous_souscategories',
        'Produits' => 'produits',
        'Liens produits catégories' => 'produit_sous_souscategorie',
    ];

    private const RELATIONS = [
        'id_brand' => 'Marques', 'id_categorie' => 'Catégories',
        'id_sous_categorie' => 'Sous-catégories', 'id_souscategorie' => 'Sous-catégories',
        'id_sous_sous_categorie' => 'Sous-sous-catégories',
        'id_produit' => 'Produits', 'produit_id' => 'Produits',
        'sous_souscategorie_id' => 'Sous-sous-catégories',
    ];

    public function handle(): int
    {
        $copied = [];
        $committed = false;
        $lock = fopen(storage_path('app/catalogue-import.lock'), 'c');
        if (! $lock || ! flock($lock, LOCK_EX | LOCK_NB)) {
            $this->error('Un autre import est en cours.');

            return self::FAILURE;
        }
        try {
            $directory = realpath($this->argument('directory'));
            if (! $directory || ! is_file($directory.'/catalogue.xlsx')) {
                throw new RuntimeException('Dossier catalogue introuvable.');
            }
            if (DB::connection()->getDriverName() !== 'mysql') {
                throw new RuntimeException('Cette sauvegarde est conçue pour MySQL/MariaDB.');
            }
            $workbook = IOFactory::load($directory.'/catalogue.xlsx');
            $data = [];
            $mapping = [];
            foreach (self::TABLES as $sheetName => $table) {
                if (DB::table($table)->exists()) {
                    throw new RuntimeException("La table {$table} doit être vide. Aucun écrasement ni doublon autorisé.");
                }
                $sheet = $workbook->getSheetByName($sheetName);
                if (! $sheet) {
                    throw new RuntimeException("Onglet manquant : {$sheetName}");
                }
                $rows = $sheet->toArray(null, false, false, false);
                $headers = array_shift($rows);
                if (count(array_unique($headers)) !== count($headers) || ! in_array('id', $headers, true)) {
                    throw new RuntimeException("En-têtes invalides : {$sheetName}");
                }
                $columns = Schema::getColumnListing($table);
                if (array_diff($headers, $columns)) {
                    throw new RuntimeException("Colonnes incompatibles : {$sheetName}");
                }
                foreach ($rows as $values) {
                    if (! array_filter($values, fn ($v) => $v !== null && $v !== '')) {
                        continue;
                    }
                    $row = array_combine($headers, $values);
                    $id = $row['id'];
                    if (! ctype_digit((string) $id) || $id <= 0 || isset($data[$sheetName][$id])) {
                        throw new RuntimeException("Identifiant invalide ou dupliqué : {$sheetName}/{$id}");
                    }
                    // Les tables vides permettent de conserver les IDs source sans collisions.
                    $mapping[$sheetName][$id] = (int) $id;
                    if ($sheetName === 'Produits') {
                        if (! is_numeric($row['prix']) || $row['prix'] < 0 || ! is_numeric($row['stock']) || (int) $row['stock'] != $row['stock']) {
                            throw new RuntimeException("Prix ou stock invalide : produit {$id}");
                        }
                        $row['id_promotion'] = null;
                        $row['gamme_id'] = null;
                        if (! empty($row['deleted_at'])) {
                            throw new RuntimeException("Produit en corbeille : {$id}");
                        }
                    }
                    if (! empty($row['photos'])) {
                        $photos = json_decode($row['photos'], true, 512, JSON_THROW_ON_ERROR);
                        if (! is_array($photos) || ! array_is_list($photos)) {
                            throw new RuntimeException("Galerie invalide : {$sheetName}/{$id}");
                        }
                    }
                    $data[$sheetName][$id] = $row;
                }
                $data[$sheetName] ??= [];
                $this->line($sheetName.': '.count($data[$sheetName]));
            }
            foreach ($data as $sheetName => &$rows) {
                foreach ($rows as $id => &$row) {
                    foreach (self::RELATIONS as $field => $target) {
                        if (! empty($row[$field])) {
                            if (! isset($mapping[$target][$row[$field]])) {
                                throw new RuntimeException("Relation absente : {$sheetName}/{$id}/{$field}={$row[$field]}");
                            }
                            $row[$field] = $mapping[$target][$row[$field]];
                        }
                    }
                }
                unset($row);
            }
            unset($rows);
            $manifest = json_decode(file_get_contents($directory.'/images.json'), true, 512, JSON_THROW_ON_ERROR);
            if (! is_array($manifest) || ! array_is_list($manifest)) {
                throw new RuntimeException('Manifeste images invalide.');
            }
            $assets = [];
            $warnings = [];
            foreach ($manifest as $image) {
                $sheetName = $image['table'];
                $id = $image['id'];
                $field = $image['champ'];
                if (! isset($data[$sheetName][$id]) || ! in_array($field, ['photo', 'photos', 'photourl'], true)) {
                    throw new RuntimeException("Association image invalide : {$sheetName}/{$id}/{$field}");
                }
                $original = $image['source'];
                if ($image['statut'] === 'incluse') {
                    $input = realpath($directory.'/'.$image['fichier']);
                    $prefix = $directory.DIRECTORY_SEPARATOR;
                    if (! $input || ! str_starts_with($input, $prefix) || ! is_file($input) || ! filesize($input) || ! @getimagesize($input)) {
                        throw new RuntimeException('Image absente ou invalide : '.$image['fichier']);
                    }
                    $extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));
                    if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'bmp'], true)) {
                        throw new RuntimeException('Format image non autorisé : '.$extension);
                    }
                    $destination = 'catalogue/'.hash_file('sha256', $input).'.'.$extension;
                    $assets[$destination] = $input;
                    // photourl est utilisé avec Storage::url par les vues des marques.
                    $replacement = $field === 'photourl' ? 'public/'.$destination : $destination;
                } elseif (preg_match('~^https?://~i', $original)) {
                    $replacement = $original;
                } else {
                    $replacement = null;
                    $warnings[] = "Image manquante : {$sheetName}/{$id}/{$field} : {$original}";
                }
                if ($field === 'photos') {
                    $photos = json_decode($data[$sheetName][$id][$field] ?? '[]', true, 512, JSON_THROW_ON_ERROR);
                    if (! in_array($original, $photos, true)) {
                        throw new RuntimeException("Image galerie non référencée : {$id}/{$original}");
                    }
                    $photos = array_values(array_filter(array_map(fn ($p) => $p === $original ? $replacement : $p, $photos), fn ($p) => $p !== null));
                    $data[$sheetName][$id][$field] = json_encode($photos, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                } else {
                    if ($data[$sheetName][$id][$field] !== $original) {
                        throw new RuntimeException("Source image incohérente : {$sheetName}/{$id}/{$field}");
                    }
                    $data[$sheetName][$id][$field] = $replacement;
                }
            }
            $this->line('Associations images : '.count($manifest).' ; fichiers uniques : '.count($assets));
            foreach ($warnings as $warning) {
                $this->warn($warning);
            }
            if ($this->option('dry-run')) {
                $this->info('Validation réussie. Aucune donnée modifiée.');

                return self::SUCCESS;
            }
            $reportDirectory = storage_path('app/catalogue-imports/'.date('Ymd-His').'-'.bin2hex(random_bytes(4)));
            File::ensureDirectoryExists($reportDirectory);
            $backup = $reportDirectory.'/database-before.sql';
            $this->backup($backup);
            $this->info('Sauvegarde complète : '.$backup);
            File::put($reportDirectory.'/id-map.json', json_encode($mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
            $disk = Storage::disk('public');
            foreach ($assets as $destination => $input) {
                if ($disk->exists($destination)) {
                    if (hash('sha256', $disk->get($destination)) !== hash_file('sha256', $input)) {
                        throw new RuntimeException('Collision fichier : '.$destination);
                    }

                    continue;
                }
                $stream = fopen($input, 'rb');
                try {
                    if (! $disk->put($destination, $stream)) {
                        throw new RuntimeException('Copie impossible : '.$destination);
                    }
                } finally {
                    fclose($stream);
                }
                $copied[] = $destination;
            }
            DB::transaction(function () use ($data) {
                foreach (self::TABLES as $sheetName => $table) {
                    if (DB::table($table)->exists()) {
                        throw new RuntimeException('Table modifiée pendant la préparation : '.$table);
                    }
                    foreach ($data[$sheetName] as $row) {
                        // Les liens inverses id_produit sont reconstruits après les produits.
                        if (in_array($table, ['souscategories', 'sous_souscategories'], true)) {
                            $row['id_produit'] = null;
                        }
                        DB::table($table)->insert($row);
                    }
                }
                foreach (['Sous-catégories', 'Sous-sous-catégories'] as $sheetName) {
                    foreach ($data[$sheetName] as $id => $row) {
                        if (! empty($row['id_produit'])) {
                            DB::table(self::TABLES[$sheetName])->where('id', $id)->update(['id_produit' => $row['id_produit']]);
                        }
                    }
                }
                // Contrôle exhaustif des champs, des relations et des totaux avant commit.
                foreach (self::TABLES as $sheetName => $table) {
                    if (DB::table($table)->count() !== count($data[$sheetName])) {
                        throw new RuntimeException('Total incorrect : '.$table);
                    }
                    foreach ($data[$sheetName] as $id => $row) {
                        $actual = (array) DB::table($table)->where('id', $id)->first();
                        foreach ($row as $field => $expected) {
                            if (($actual[$field] === null) !== ($expected === null) || ($actual[$field] !== null && (string) $actual[$field] !== (string) $expected)) {
                                throw new RuntimeException("Vérification échouée : {$table}/{$id}/{$field}");
                            }
                        }
                    }
                }
            });
            $committed = true;
            File::put($reportDirectory.'/report.json', json_encode([
                'database' => DB::connection()->getDatabaseName(),
                'source' => $directory,
                'workbook_sha256' => hash_file('sha256', $directory.'/catalogue.xlsx'),
                'counts' => array_map('count', $data),
                'image_associations' => count($manifest), 'unique_files' => count($assets),
                'warnings' => $warnings, 'verified' => true,
                'promotions_and_ranges' => 'id_promotion et gamme_id vidés',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
            $copied = [];
            $this->info('Import terminé et vérifié. Rapport : '.$reportDirectory.'/report.json');

            return self::SUCCESS;
        } catch (Throwable $e) {
            foreach ($committed ? [] : $copied as $destination) {
                Storage::disk('public')->delete($destination);
            }
            $this->error($e->getMessage());

            return self::FAILURE;
        } finally {
            if ($lock) {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    private function backup(string $path): void
    {
        $pdo = DB::connection()->getPdo();
        $tables = DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        $output = fopen($path, 'xb');
        if (! $output) {
            throw new RuntimeException('Impossible de créer la sauvegarde SQL.');
        }
        $write = function (string $sql) use ($output): void {
            if (fwrite($output, $sql) !== strlen($sql)) {
                throw new RuntimeException('Écriture de la sauvegarde SQL interrompue.');
            }
        };
        try {
            DB::transaction(function () use ($tables, $pdo, $write) {
                $write("-- Sauvegarde avant import du catalogue\nSET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n");
                foreach ($tables as $entry) {
                    $table = array_values((array) $entry)[0];
                    $quoted = chr(96).str_replace(chr(96), chr(96).chr(96), $table).chr(96);
                    $ddl = array_values((array) DB::selectOne('SHOW CREATE TABLE '.$quoted))[1];
                    $write('DROP TABLE IF EXISTS '.$quoted.";\n".$ddl.";\n");
                    $statement = $pdo->query('SELECT * FROM '.$quoted);
                    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                        $columns = implode(',', array_map(fn ($column) => chr(96).str_replace(chr(96), chr(96).chr(96), $column).chr(96), array_keys($row)));
                        $values = implode(',', array_map(fn ($value) => $value === null ? 'NULL' : "X'".bin2hex((string) $value)."'", array_values($row)));
                        $write('INSERT INTO '.$quoted.' ('.$columns.') VALUES ('.$values.");\n");
                    }
                    $statement->closeCursor();
                }
                $write("SET FOREIGN_KEY_CHECKS=1;\n");
            });
        } finally {
            fclose($output);
        }
    }
}
