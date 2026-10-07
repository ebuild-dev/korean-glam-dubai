<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contenu_commande extends Model
{
    use HasFactory;



    public function produit(){
        return $this->belongsTo(produits::class ,'id_produit')->withTrashed();
    }

    public function commande(){
        return $this->belongsTo(commandes::class ,'id_commande');
    }
   
    public function pack(){
        return $this->belongsTo(packs::class ,'id_pack');
    }

}
