<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mother_id',
        'pharmacist_id',
        'medication_name',
        'dosage_instructions',
    ];

    /**
     * Get the expectant mother that this prescription belongs to.
     */
    public function mother()
    {
        return $this->belongsTo(ExpectantMother::class, 'mother_id');
    }

    /**
     * Get the pharmacist who dispensed this prescription.
     */
    public function pharmacist()
    {
        return $this->belongsTo(Pharmacist::class, 'pharmacist_id');
    }
}