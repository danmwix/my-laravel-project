<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class ExpectantMother extends Authenticatable
{
    use Notifiable;

    protected $table = 'expectant_mothers';
    protected $primaryKey = 'mother_id';

    protected $fillable = [
        'full_name', 'email', 'password_hash', 'dob', 'age',
        'place_of_residence', 'due_date', 'phone'
    ];
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = bcrypt($value);
    }
    
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'mother_id');
    }

    public function physicalExamFindings()
    {
        return $this->hasOne(PhysicalExamFinding::class, 'mother_id');
    }
}   
