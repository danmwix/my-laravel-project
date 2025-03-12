<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalExamFinding extends Model
{
    protected $fillable = [
        'mother_id', 'abdominal_exam', 'urinalysis', 'blood_test',
        'blood_pressure', 'ultrasound', 'treatment_plan'
    ];

    public function mother()
    {
        return $this->belongsTo(ExpectantMother::class, 'mother_id');
    }
}