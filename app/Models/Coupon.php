<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;



    public function commandes(){
        return $this->hasMany(commandes::class,"coupon_id");
    }


    public function Auteur(){
        return $this->belongsTo(User::class , 'by');
    }

    public function estActif(){
        // c'est actif si la date actuel est entre la date de created_at et fun valid_until
        return $this->created_at <= now() && now() <= $this->valid_until;
    }


}
