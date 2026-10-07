<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousSousCategorie extends Model
{
    use HasFactory;

    protected $table = 'sous_souscategories';

    protected $fillable = [
        'nom',
        'order_column',
        'id_categorie',
        'id_produit',
        'id_souscategorie',
    ];
    // Relations
    public function categorie()
    {
        return $this->belongsTo(categories::class, 'id_categorie');
    }

    public function produit()
    {
        return $this->belongsTo(produits::class, 'id_produit');
    }

    public function souscategorie()
    {
        return $this->belongsTo(SousCategorie::class, 'id_souscategorie');
    }
    public function produits()
    {
        return $this->belongsToMany(produits::class, 'produit_sous_souscategorie', 'sous_souscategorie_id', 'produit_id')
            ->withTimestamps();
    }


}
