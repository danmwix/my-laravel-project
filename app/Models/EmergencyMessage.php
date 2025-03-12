<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyMessage extends Model
{
    protected $table = 'emergency_messages';
    // Add 'name' and 'phone' to the fillable array
    protected $fillable = ['mother_id', 'name', 'phone', 'message'];

    public function expectantMother()
    {
        return $this->belongsTo(ExpectantMother::class, 'mother_id');
    }
}