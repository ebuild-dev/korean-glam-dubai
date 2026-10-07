<?php

namespace App\Console\Commands;

use App\Models\produits;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/shop'));

        produits::where('masquer_produit', false)->get()->each(function ($produit) use ($sitemap) {
            $sitemap->add(
                Url::create(route('produit', [
                    'id' => $produit->id,
                    'slug' => \Str::slug($produit->nom)
                ]))
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
