<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pharmacist extends Authenticatable
{
    use Notifiable;

    protected $guard = 'pharmacist';

    protected $fillable = [
        'full_name', 'registration_number', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'registration_number';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}