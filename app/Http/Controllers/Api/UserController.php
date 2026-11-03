<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{ActivityLog,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController extends Controller {
    public function index(Request $r){$q=User::query();if($r->search)$q->where(fn($x)=>$x->where('name','like',"%{$r->search}%")->orWhere('email','like',"%{$r->search}%"));if($r->role)$q->where('role',$r->role);return response()->json($q->orderBy('name')->paginate(max(1,min((int) $r->input('per_page',10),50))));}
    public function store(Request $r){$data=$r->validate(['name'=>'required|string|max:120','email'=>'required|email|unique:users','role'=>'required|in:admin,receptionist,doctor','password'=>'required|string|min:8','is_active'=>'boolean']);$data['password']=Hash::make($data['password']);$u=User::create($data);ActivityLog::record($r->user(),'create',$u,"Created user {$u->email}.");return response()->json(['data'=>$u,'message'=>'User created successfully.'],201);}
    public function update(Request $r,User $user){$data=$r->validate(['name'=>'sometimes|string|max:120','email'=>['sometimes','email',Rule::unique('users')->ignore($user)],'role'=>'sometimes|in:admin,receptionist,doctor','password'=>'nullable|string|min:8','is_active'=>'sometimes|boolean']);if(empty($data['password']))unset($data['password']);else$data['password']=Hash::make($data['password']);$user->update($data);ActivityLog::record($r->user(),'update',$user,"Updated user {$user->email}.");return response()->json(['data'=>$user,'message'=>'User updated successfully.']);}
}
