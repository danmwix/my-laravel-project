<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    // app/Models/Appointment.php
protected $fillable = ['mother_id', 'nurse_id', 'date', 'time', 'reason', 'return_date'];

    public function mother()
    {
        return $this->belongsTo(ExpectantMother::class, 'mother_id');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class, 'nurse_id', 'nurse_id'); // Specify nurse_id as the key
    }
}
