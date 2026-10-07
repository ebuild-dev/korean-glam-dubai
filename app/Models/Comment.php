<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'commentaire',
        'user_id',
        'produit_id',
        'photo',
        'valide',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function produit()
    {
        return $this->belongsTo(produits::class);
    }

}
