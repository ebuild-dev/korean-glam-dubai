<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour automatiquement les points_achat et points_vente de tous les produits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('⏳ Mise à jour des points en cours...');

        DB::statement("
            UPDATE produits
            SET points_achat = prix,
                points_vente = prix * 0.05
        ");

        $this->info('✅ Tous les produits ont été mis à jour avec succès !');
    }
}
