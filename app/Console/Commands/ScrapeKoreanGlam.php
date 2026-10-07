<?php

namespace App\Console\Commands;

use App\Services\KoreanGlamParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class ScrapeKoreanGlam extends Command
{
    protected $signature = 'catalog:scrape-korean-glam {--resume : Ignorer les fiches déjà importées} {--delay=500 : Pause entre requêtes en millisecondes}';
    protected $description = 'Importer le catalogue public Korean Glam dans scraped_products (MySQL configuré).';

    public function handle(KoreanGlamParser $parser): int
    {
        if (!Schema::hasTable('scraped_products')) {
            $this->error('Exécutez la migration create_scraped_products_table avant cet import.');
            return self::FAILURE;
        }
        if (!ctype_digit((string) $this->option('delay'))) {
            $this->error('--delay doit être un entier positif ou nul.');
            return self::FAILURE;
        }
        $client = new Client([
            'base_uri' => 'https://korean-glam.tn', 'cookies' => true,
            'timeout' => 30, 'connect_timeout' => 10,
            'headers' => ['User-Agent' => 'KoreanGlamCatalogImporter/1.0', 'Accept' => 'text/html,application/json'],
        ]);
        $request = function (string $method, string $path, array $options = []) use ($client): string {
            for ($attempt = 0; ; $attempt++) {
                usleep((int) $this->option('delay') * 1000);
                try {
                    return (string) $client->request($method, $path, $options)->getBody();
                } catch (Throwable $e) {
                    if ($attempt >= 2) throw $e;
                    sleep($attempt + 1);
                }
            }
        };
        $seen = [];
        $errors = [];
        $imported = 0;
        $skipped = 0;
        $expected = null;
        try {
            $shop = $request('GET', '/shop');
            $meta = $parser->document($shop)->query('//meta[@name="csrf-token"]')->item(0);
            if (!$meta || !$meta->getAttribute('content')) throw new RuntimeException('Jeton CSRF absent de /shop.');
            $token = $meta->getAttribute('content');
            for ($page = 1; $page <= 10000; $page++) {
                $result = json_decode($request('POST', '/shop/ajax?page='.$page, [
                    'form_params' => ['_token' => $token],
                    'headers' => ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json', 'Referer' => 'https://korean-glam.tn/shop'],
                ]), true, 512, JSON_THROW_ON_ERROR);
                if (!isset($result['html'], $result['total']) || !is_numeric($result['total'])) throw new RuntimeException('Réponse catalogue invalide.');
                $expected ??= (int) $result['total'];
                if ($expected !== (int) $result['total']) throw new RuntimeException('Le catalogue a changé pendant la collecte. Relancez avec --resume.');
                $ids = $parser->productIds($result['html']);
                $newIds = array_values(array_diff($ids, array_keys($seen)));
                if (!$newIds) {
                    if (count($seen) < $expected) throw new RuntimeException('Pagination vide ou répétée avant la fin du catalogue.');
                    break;
                }
                $this->info("Page {$page} : ".count($newIds).' produits');
                foreach ($newIds as $id) {
                    $seen[$id] = true;
                    if ($this->option('resume') && DB::table('scraped_products')->where('source', 'korean-glam.tn')->where('source_product_id', $id)->exists()) {
                        $skipped++;
                        continue;
                    }
                    try {
                        $data = $parser->parse($request('GET', '/produit/'.$id), $id);
                        $data['scraped_at'] = $data['updated_at'] = $data['created_at'] = now();
                        DB::table('scraped_products')->upsert([$data], ['source', 'source_product_id'], array_diff(array_keys($data), ['source', 'source_product_id', 'created_at']));
                        $imported++;
                    } catch (Throwable $e) {
                        $errors[$id] = $e->getMessage();
                        $this->warn("Produit {$id} : ".$e->getMessage());
                    }
                }
                if (count($seen) >= $expected) break;
            }
            if (count($seen) !== $expected) throw new RuntimeException('Nombre de produits collectés différent du total annoncé.');
        } catch (Throwable $e) {
            $errors['catalogue'] = $e->getMessage();
            $this->error($e->getMessage());
        }
        $report = ['source' => 'https://korean-glam.tn/shop', 'date' => now()->toIso8601String(), 'expected' => $expected, 'discovered' => count($seen), 'imported' => $imported, 'skipped' => $skipped, 'errors' => $errors];
        file_put_contents(storage_path('app/korean-glam-scrape-report.json'), json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        $this->info("Importés : {$imported}; déjà présents : {$skipped}; erreurs : ".count($errors));
        return $errors ? self::FAILURE : self::SUCCESS;
    }
}
