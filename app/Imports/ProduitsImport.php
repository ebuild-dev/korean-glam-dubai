<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\categories;
use App\Models\produits;
use App\Models\Souscategorie;
use App\Models\SousSousCategorie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ProduitsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        // === Gestion image ===
     /*   $imagePath = null;
        if (!empty($row[2])) {
            $sourcePath = storage_path('app/import_images/' . $row[2]);
            if (file_exists($sourcePath)) {
                // remplacer les caractères spéciaux par "_"
                $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $row[2]);
                $fileName = 'produits/' . Str::random(10) . '_' . $cleanName;
                Storage::disk('public')->put($fileName, file_get_contents($sourcePath));
                $imagePath = $fileName;
            }
        }*/


     /*   // === Gestion catégories ===
        $categorieId = null;
        $sousCategorieId = null;
        $sousSousCategorieId = null;

        if (!empty($row[4])) {
            $categorie = categories::firstOrCreate(['nom' => trim($row[4])]);
            $categorieId = $categorie->id;
            if (!empty($row[5])) { // sous_categorie
                $sousCategorie = SousCategorie::firstOrCreate(['nom' => trim($row[5]), 'id_categorie' => $categorieId]);
                $sousCategorieId = $sousCategorie->id;
                if (!empty($row[6])) { // sous_sous_categorie
                    $sousSousCategorie = SousSousCategorie::firstOrCreate(['nom' => trim($row[6]), 'id_souscategorie' => $sousCategorieId], ['id_categorie' => $categorieId]);
                    $sousSousCategorieId = $sousSousCategorie->id;
                }
            }
        }

        // === Gestion marque ===
        $brandId = null;
        if (!empty($row[3])) { // colonne "brand"
            $brand = Brand::firstOrCreate(
                ['brandname' => trim($row[3])], // colonnes pour vérifier si la marque existe
                [                               // colonnes à créer si elle n'existe pas
                    'ishome' => 0,
                    'brandurl' => trim($row[3])
                ]
            );
            $brandId = $brand->id;
        }*/
        if ($row[0] === 'Product Image' || $row[2] === 'Product Name') {
            return null;
        }

        // === Télécharger l'image ===
        $imageUrl = $row[0] ?? null;
        $imagePath = null;

        if (!empty($imageUrl)) {
            try {
                $response = Http::get($imageUrl);

                if ($response->successful()) {
                    // Extraire extension de l’URL
                    $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';

                    // Générer un nom unique
                    $fileName = uniqid('produit_') . '.' . $extension;

                    // Sauvegarde dans storage/app/public/produits/
                    Storage::disk('public')->put("produits/{$fileName}", $response->body());

                    // Chemin accessible via storage:link
                    $imagePath = "produits/{$fileName}";
                }
            } catch (\Exception $e) {
                Log::error("⚠️ Erreur téléchargement image : {$imageUrl} - " . $e->getMessage());
            }
        }
        return new Produits([
            'nom'         => $row[2], // Product Name
            'description' => $row[1], // URL (ou bien mettre vide si tu veux une vraie description après)
            'photo'       => $imagePath ?: $row[0], // image locale sinon URL brute
            'prix'        => $row[3], // Price
            'stock'       => 100,     // Valeur par défaut
            'reference' => $row[2],
    /*        'id_categorie' => $categorieId,
            'id_sous_categorie' => $sousCategorieId,
            'id_sous_sous_categorie' => $sousSousCategorieId,
            'id_brand' => $brandId,*/
            'slug' => Str::slug($row[2]),
        ]);
    }
}
