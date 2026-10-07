<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class packs extends Model
{
    use HasFactory;


    public function contenus(){
        return $this->hasMany(contenu_pack::class,'id_pack','id');
    }

    public function photo(){
        return "https://img.icons8.com/ios/100/027461/pack-luggage.png";
    }
}
