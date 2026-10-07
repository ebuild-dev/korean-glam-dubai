<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historiques_stock extends Model
{
    use HasFactory;
    

    public function  produit() {
        return $this->belongsTo(produits::class , 'id_produit');
    }


    public function Auteur(){
        return $this->belongsTo(User::class , 'by');
    }


}
