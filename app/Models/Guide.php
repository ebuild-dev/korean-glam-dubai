<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'skin_type',
        'skin_concern',
        'skin_condition',
        'weekly_exfoliator',
        'weekly_face_mask',
        'weekly_wash_mask',
        'id_appointment'
    ];

    public function routines()
    {
        return $this->hasMany(SkincareRoutine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment');
    }
}
