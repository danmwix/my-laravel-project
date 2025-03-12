<?php

// app/Models/MaternityRecord.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaternityRecord extends Model
{
    protected $fillable = ['mother_id', 'weight', 'blood_pressure', 'temperature', 'height', 'respiratory_rate'];

    public function mother()
    {
        return $this->belongsTo(ExpectantMother::class, 'mother_id');
    }
}