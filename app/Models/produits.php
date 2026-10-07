<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class produits extends Model
{
    use HasFactory, SoftDeletes;
    // Colonnes autorisées pour l'insertion massive
    protected $fillable = [
        'nom',
        'description',
        'reference',
        'photo',
        'photos',
        'prix',
        'id_categorie',
        'id_sous_categorie',
        'id_sous_sous_categorie',
        'slug',
        'id_brand',
        'stock',
        'gamme_id',
        'points_achat',
        'points_vente',
        'masquer_produit',
        'best_sellers',
        'pris_order',
        'trend'
    ];
    protected $casts = [
        'exclusive' => 'boolean',

    ];

    public function getSlugAttribute()
    {
        return Str::slug($this->nom ?? '');
    }

    public function vendus()
    {
        return $this->hasMany(contenu_commande::class, 'id_produit');
    }

    public function getPrice()
    {
        //recuperer le prix en fonction du pays selectioner
        $pays =  request()->cookie('countryName') ?? "TN";
        $prix = $this->prix;
        if ($this->id_promotion) {
            $promotion = promotions::find($this->id_promotion);
            if ($promotion) {
                $price = $prix - ($prix * ($promotion->pourcentage / 100));
                return $price;
            } else {
                return $prix;
            }
        } else {
            return $prix;
        }
    }


    public function getPrixVente()
    {
        $pays =  request()->cookie('countryName') ?? "TN";
        if ($pays == "TN") {
            return $this->prix;
        } else {
            return $this->prix_fr;
        }
    }


    public function Get_price_sans_tva($valeur_tva, $prix_unitaire)
    {
        $prix_sans_tva = $prix_unitaire / (1 + ($valeur_tva / 100));
        return number_format($prix_sans_tva, 3, '.', '');
    }

    public function Get_valeur_tva($valeur_tva, $prix_unitaire)
    {
        $prix_sans_tva = $prix_unitaire / (1 + ($valeur_tva / 100));
        return number_format($prix_unitaire - $prix_sans_tva, 3, '.', '');
    }


    public function inPromotion()
    {
        if ($this->relationLoaded('promotion')) {
            return $this->getRelation('promotion') ?: false;
        }

        if ($this->id_promotion) {
            $promotion = promotions::find($this->id_promotion);
            if ($promotion) {
                return $promotion;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function diminuer_stock(int $quantite): void
    {
        if ($this->stock >= $quantite) {
            $this->stock -= $quantite;
            $this->save();
        }
    }

    public function retourner_stock(int $quantite): void
    {
        $this->stock += $quantite;
        $this->save();
    }


    public function historique_stock()
    {
        return $this->hasMany(historiques_stock::class, 'id_produit');
    }


    public function vues()
    {
        return $this->hasMany(views::class, 'id_produit');
    }

    public function categorie()
    {
        return $this->belongsTo(categories::class, 'id_categorie');
    }

    public function sous_categorie()
    {
        return $this->belongsTo(Souscategorie::class, 'id_sous_categorie');
    }


    public function FirstImage()
    {
        return self::imageUrl($this->photo);
    }

    public static function imageUrl(?string $photo): string
    {
        if (!$photo) {
            return asset('def.jpg');
        }

        if (preg_match('~^(https?://|//)~i', $photo)) {
            return $photo;
        }

        $photo = ltrim(str_replace('\\', '/', $photo), '/');
        $photo = preg_replace('~^(?:storage/|public/)~', '', $photo);

        try {
            $disk = Storage::disk('public');
            if ($disk->exists($photo) && $disk->size($photo) > 0) {
                return asset('storage/' . $photo);
            }
        } catch (\Exception $e) {
            return asset('def.jpg');
        }

        return asset('def.jpg');
    }

    public function marque()
    {
        return $this->belongsTo(Brand::class, 'id_brand');
    }

    public function promotion()
    {
        return $this->belongsTo(promotions::class, 'id_promotion');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'produit_id');
    }
    public function gamme()
    {
        return $this->belongsTo(Gamme::class, 'gamme_id');
    }
    public function sousSousCategories()
    {
        return $this->belongsToMany(SousSousCategorie::class, 'produit_sous_souscategorie', 'produit_id', 'sous_souscategorie_id')
            ->withTimestamps();
    }


}
