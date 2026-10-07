<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commandes extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'paymentRef',
        'statut',
        'frais',
        'is_point',
        'points_utilises',
        'montant_restant'
    ];
    protected $casts = [
        'is_point' => 'boolean',
    ];

    public function contenus()
    {
        return $this->hasMany(contenu_commande::class, 'id_commande');
    }

    public function montant(){
        $config= config::first();

        $total = ($this->frais + $this->timbre) - $this->reduction;
        foreach ($this->contenus as $contenu){
            $total += $contenu->prix_unitaire * $contenu->quantite;

        }
        $total+= $config->frais;
        return $total ?? 0;
    }

    public function montant_with_coupon(){


        $montant = $this->montant();
        if($this->coupon){
            $pourcentage_reduction = $this->coupon->pourcentage;
            $montant -= $montant * $pourcentage_reduction / 100;
            return $montant;
        }
        return $montant;
    }

    public function client(){
        return $this->belongsTo(clients::class, 'phone','phone');
    }

    public function modifiable(){
        if ($this->statut === 'retournée' || $this->statut === 'payée' || $this->statut === 'livrée') {
            return false;
        } else {
            return true;
        }
    }

    public function montant_ht(){
        return $this->montant() / + $this->tva /10;
    }

    public function montant_ttc(){
        return $this->montant();
    }



    public function ProduitsText(){
        $produits = $this->contenus()->get();
        $text = "";
        foreach ($produits as $produit){
            $text.= $produit->produit->nom. " x ". $produit->quantite. " | ";
        }
        return $text;
    }


    public function Auteur(){
        return $this->belongsTo(User::class , 'by');
    }

    public function gouvernorat(){
        return $this->belongsTo(gouvernorats::class, 'id_gouvernorat','id');
    }



    public function coupon(){
        return $this->belongsTo(Coupon::class, 'coupon_id','id');
    }

    public function hasPreorder()
    {
        return $this->contenus()
            ->whereHas('produit', function ($q) {
                $q->where('pris_order', true);
            })->exists();
    }

}
