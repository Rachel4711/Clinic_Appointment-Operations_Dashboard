<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Appointment extends Model {
    use HasFactory;
    protected $fillable = ['patient_id','doctor_id','appointment_date','start_time','end_time','reason','status','notes','created_by'];
    protected $casts = ['appointment_date'=>'date:Y-m-d'];
    public function patient(){ return $this->belongsTo(Patient::class); }
    public function doctor(){ return $this->belongsTo(Doctor::class); }
    public function creator(){ return $this->belongsTo(User::class, 'created_by'); }
}
