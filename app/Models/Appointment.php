<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'email',
        'telephone',
        'file',
        'notes',
        'availability',
        'date_appointment',
        'meeting_code',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function guides()
    {
        return $this->hasMany(Guide::class, 'id_appointment');
    }
}
