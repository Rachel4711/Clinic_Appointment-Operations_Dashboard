<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{ActivityLog,Patient};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class PatientController extends Controller {
    private function rules($patient=null){ return ['patient_number'=>['required','string','max:30',Rule::unique('patients')->ignore($patient)],'full_name'=>'required|string|max:150','date_of_birth'=>'required|date|before:today','gender'=>'nullable|in:Female,Male,Other,Prefer not to say','phone'=>'required|string|max:30','email'=>'nullable|email|max:150','address'=>'nullable|string|max:1000','emergency_contact'=>'nullable|string|max:150']; }
    public function index(Request $r){ $q=Patient::query(); if($s=$r->search){$q->where(fn($x)=>$x->where('full_name','like',"%$s%")->orWhere('patient_number','like',"%$s%")->orWhere('phone','like',"%$s%"));} return response()->json($q->orderBy($r->get('sort','full_name'),$r->get('direction')==='desc'?'desc':'asc')->paginate(max(1,min((int) $r->input('per_page',10),50)))); }
    public function store(Request $r){$p=Patient::create($r->validate($this->rules())); ActivityLog::record($r->user(),'create',$p,"Created patient {$p->patient_number}."); return response()->json(['data'=>$p,'message'=>'Patient created successfully.'],201);}
    public function show(Patient $patient){return response()->json(['data'=>$patient->load('appointments.doctor')]);}
    public function update(Request $r, Patient $patient){$patient->update($r->validate($this->rules($patient))); ActivityLog::record($r->user(),'update',$patient,"Updated patient {$patient->patient_number}."); return response()->json(['data'=>$patient,'message'=>'Patient updated successfully.']);}
    public function destroy(Request $r, Patient $patient){ if($patient->appointments()->exists()) return response()->json(['message'=>'Patients with appointment history cannot be deleted.'],422); ActivityLog::record($r->user(),'delete',$patient,"Deleted patient {$patient->patient_number}."); $patient->delete(); return response()->json(null,204);}
}
