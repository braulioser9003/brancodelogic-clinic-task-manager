<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialty', 'phone'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function surgeries()
    {
        return $this->hasMany(Surgery::class);
    }
}

