<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchJaxStatut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-jax-statut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exécute une route spécifique toutes les 4 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Appelle ta route ici
        $response = Http::get(route('init-refresh'));

        // Logique supplémentaire si nécessaire
        if ($response->successful()) {
            Log::error('--> bonne execution de la route');
        } else {
            Log::error('Erreur lors de l\'exécution de la route de fetch api');
        }
    }
}
