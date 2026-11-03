<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Patient extends Model {
    use HasFactory;
    protected $fillable = ['patient_number','full_name','date_of_birth','gender','phone','email','address','emergency_contact'];
    protected $casts = ['date_of_birth'=>'date:Y-m-d'];
    public function appointments(){ return $this->hasMany(Appointment::class); }
}
