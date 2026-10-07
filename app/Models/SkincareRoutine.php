<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkincareRoutine extends Model
{
    use HasFactory;
    protected $fillable = [
        'guide_id',
        'type',
        'cleanser',
        'toner',
        'serum',
        'eye_cream',
        'cream',
        'spf',
        'others',
        'second_cleanser',
        'spot_treatment',
    ];
    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }
}
