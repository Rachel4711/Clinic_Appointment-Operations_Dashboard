<?php
namespace Tests\Feature;
use App\Models\{Appointment,Doctor,IssueReport,Patient,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
class ClinicApiTest extends TestCase {
 use RefreshDatabase;
 private function user($role='admin'){return User::factory()->create(['role'=>$role,'is_active'=>true,'password'=>Hash::make('password123')]);}
 public function test_login_succeeds_and_fails(){ $u=$this->user();$this->withHeader('Origin','http://localhost')->postJson('/api/login',['email'=>$u->email,'password'=>'password123'])->assertOk()->assertJsonPath('data.role','admin');$this->postJson('/api/login',['email'=>$u->email,'password'=>'wrong-password'])->assertStatus(422); }
 public function test_unauthenticated_access_is_rejected(){ $this->getJson('/api/dashboard')->assertUnauthorized(); }
 public function test_roles_are_enforced(){ Sanctum::actingAs($this->user('receptionist'));$this->getJson('/api/users')->assertForbidden(); }
 public function test_patient_creation_and_validation(){ Sanctum::actingAs($this->user('receptionist'));$data=['patient_number'=>'PT-TEST-1','full_name'=>'Fictional Person','date_of_birth'=>'1990-01-01','phone'=>'0123456789'];$this->postJson('/api/patients',$data)->assertCreated();$this->postJson('/api/patients',[])->assertStatus(422)->assertJsonValidationErrors(['patient_number','full_name']); }
 public function test_patient_index_supports_and_limits_page_size(){ Sanctum::actingAs($this->user('receptionist'));Patient::factory()->count(3)->create();$this->getJson('/api/patients?per_page=2')->assertOk()->assertJsonCount(2,'data')->assertJsonPath('per_page',2);$this->getJson('/api/patients?per_page=0')->assertOk()->assertJsonPath('per_page',1); }
 public function test_appointment_conflicts_are_rejected(){ $user=$this->user('receptionist');Sanctum::actingAs($user);$patient=Patient::factory()->create();$doctor=Doctor::factory()->create();$data=['patient_id'=>$patient->id,'doctor_id'=>$doctor->id,'appointment_date'=>today()->format('Y-m-d'),'start_time'=>'09:00','end_time'=>'09:30','reason'=>'Demo visit'];$this->postJson('/api/appointments',$data)->assertCreated();$data['start_time']='09:15';$data['end_time']='09:45';$this->postJson('/api/appointments',$data)->assertStatus(422)->assertJsonValidationErrors('start_time'); }
 public function test_appointment_status_issue_and_dashboard(){ $user=$this->user('receptionist');Sanctum::actingAs($user);$patient=Patient::factory()->create();$doctor=Doctor::factory()->create();$a=Appointment::create(['patient_id'=>$patient->id,'doctor_id'=>$doctor->id,'appointment_date'=>today(),'start_time'=>'09:00','end_time'=>'09:30','reason'=>'Demo','status'=>'Scheduled','created_by'=>$user->id]);$this->patchJson("/api/appointments/{$a->id}",['status'=>'Completed'])->assertOk()->assertJsonPath('data.status','Completed');$this->postJson('/api/issue-reports',['title'=>'Demo issue','description'=>'Fictional operational issue','priority'=>'Low'])->assertCreated();$this->getJson('/api/dashboard')->assertOk()->assertJsonStructure(['data'=>['counts','recent_appointments','recent_issues']]); }
}
