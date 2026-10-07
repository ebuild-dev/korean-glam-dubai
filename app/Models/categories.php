<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;

    protected $fillable = ['nom']; // <-- ajouter ici toutes les colonnes que tu remplis

    public function produits(){
        return $this->hasMany(produits::class, 'id_categorie','id');
    }
    public function souscategories(){
        return $this->hasMany(Souscategorie::class, 'id_categorie','id');
    }

    public function soussouscategories(){
        return $this->hasMany(SousSousCategorie::class, 'id_categorie','id');
    }


}
