<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souscategorie extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'id_categorie'];

    public function categorie()
    {
        return $this->belongsTo(Categories::class, 'id_categorie');
    }

    public function produits()
    {
        return $this->hasMany(produits::class, 'id_sous_categorie');
    }
    public function soussouscategories()
    {
        return $this->hasMany(SousSousCategorie::class, 'id_souscategorie','id');
    }
}
