<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'brandname',  // <--- ajouter cette colonne
        'ishome',
        'brandurl',
    ];

    public function produits(){
        return $this->hasMany(produits::class, 'id_brand','id');
    }

}
