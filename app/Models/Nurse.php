<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nurse extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'nurses';
    protected $primaryKey = 'nurse_id';

    protected $fillable = [
        'full_name',
        'registration_number',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $guarded = ['nurse_id'];


    public function getAuthIdentifierName()
    {
        return 'registration_number';
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'nurse_id');
    }

   
}