<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\produits;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp {--delete-original}';
    protected $description = 'Convert existing product images to WEBP';

    public function handle()
    {
        $this->info('🚀 Conversion des images vers WEBP...');

        $manager = new ImageManager(new Driver());

        $produits = produits::whereNotNull('photo')->get();

        foreach ($produits as $produit) {

            $path = $produit->photo;

            // Skip si déjà webp
            if (str_ends_with($path, '.webp')) {
                continue;
            }

            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                $this->warn("❌ Image introuvable : $path");
                continue;
            }

            $webpPath = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $path);
            $webpFullPath = storage_path('app/public/' . $webpPath);

            try {
                $image = $manager->read($fullPath);
                $image->toWebp(100)->save($webpFullPath);

                // Mise à jour DB
                $produit->photo = $webpPath;
                $produit->save();

                // Supprimer l’original si option activée
                if ($this->option('delete-original')) {
                    unlink($fullPath);
                }

                $this->info("✔ {$path} → {$webpPath}");

            } catch (\Throwable $e) {
                $this->error("⚠ Erreur sur {$path} : {$e->getMessage()}");
            }
        }

        $this->info('✅ Conversion terminée');
    }
}
