<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{ActivityLog,Doctor};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class DoctorController extends Controller {
    private function rules($doctor=null){return ['user_id'=>['nullable','exists:users,id',Rule::unique('doctors')->ignore($doctor)],'name'=>'required|string|max:150','specialisation'=>'required|string|max:100','phone'=>'required|string|max:30','email'=>['required','email','max:150',Rule::unique('doctors')->ignore($doctor)],'availability_status'=>'required|in:Available,Unavailable,On Leave'];}
    public function index(Request $r){$q=Doctor::with('user:id,name,email'); if($s=$r->search)$q->where(fn($x)=>$x->where('name','like',"%$s%")->orWhere('specialisation','like',"%$s%")); if($r->availability_status)$q->where('availability_status',$r->availability_status); return response()->json($q->orderBy('name')->paginate(max(1,min((int) $r->input('per_page',10),50))));}
    public function store(Request $r){$d=Doctor::create($r->validate($this->rules()));ActivityLog::record($r->user(),'create',$d,"Created doctor {$d->name}.");return response()->json(['data'=>$d,'message'=>'Doctor created successfully.'],201);}
    public function show(Doctor $doctor){return response()->json(['data'=>$doctor->load('user:id,name,email')]);}
    public function update(Request $r,Doctor $doctor){$doctor->update($r->validate($this->rules($doctor)));ActivityLog::record($r->user(),'update',$doctor,"Updated doctor {$doctor->name}.");return response()->json(['data'=>$doctor,'message'=>'Doctor updated successfully.']);}
    public function destroy(Request $r,Doctor $doctor){if($doctor->appointments()->exists())return response()->json(['message'=>'Doctors with appointment history cannot be deleted.'],422);ActivityLog::record($r->user(),'delete',$doctor,"Deleted doctor {$doctor->name}.");$doctor->delete();return response()->json(null,204);}
}
