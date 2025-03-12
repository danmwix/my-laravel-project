<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use Notifiable;

    protected $guard = 'doctor'; // Ensures authentication is under the correct guard

    protected $fillable = [
        'full_name', 'registration_number', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
