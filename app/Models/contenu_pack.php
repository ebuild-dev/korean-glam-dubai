<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class contenu_pack extends Model
{
    use HasFactory,SoftDeletes;


    public function produit(){
        return $this->belongsTo(produits::class ,'id_produit')->withTrashed();
    }

    public function pack(){
        return $this->belongsTo(packs::class ,'id_pack');
    }
}
