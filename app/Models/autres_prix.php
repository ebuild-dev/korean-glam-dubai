<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autres_prix extends Model
{
    protected $table = 'autres_prix';

    protected $fillable = [
        'id_produit',
        'quantite',
        'prix',
    ];

}
