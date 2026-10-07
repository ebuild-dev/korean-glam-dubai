<?php

namespace App\Console\Commands;

use App\Imports\ProduitsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportProduits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:produits {file=Tunisia-Marka-Products_Feuil1_.xlsx}';

    protected $description = 'Importer les produits depuis un fichier Excel et insérer dans la base de données';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = storage_path('app/' . $this->argument('file'));

        if (!file_exists($file)) {
            $this->error("❌ Le fichier {$file} n'existe pas.");
            return 1;
        }

        try {
            Excel::import(new ProduitsImport(), $file);
            $this->info('✅ Importation terminée avec succès !');
        } catch (\Exception $e) {
            $this->error("⚠️ Erreur lors de l’import : " . $e->getMessage());
        }

        return 0;
    }
}
