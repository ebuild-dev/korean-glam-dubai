<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gamme extends Model
{
    use HasFactory;

    protected $table = 'gammes';
    protected $fillable = [
        'nom',
        'description',
        'order_column',
        'photo',
    ];

    public function produits()
    {
        return $this->hasMany(produits::class, 'gamme_id');
    }

}
