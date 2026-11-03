<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Appointment,Doctor,IssueReport,Patient,User};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('ClinicDemo!2026');
        $admin = User::create(['name'=>'Amina Rahman','email'=>'admin@riverbend.test','password'=>$password,'role'=>'admin','is_active'=>true]);
        $receptionist = User::create(['name'=>'Daniel Tan','email'=>'reception@riverbend.test','password'=>$password,'role'=>'receptionist','is_active'=>true]);
        $doctorUser = User::create(['name'=>'Dr. Maya Chen','email'=>'doctor@riverbend.test','password'=>$password,'role'=>'doctor','is_active'=>true]);
        $doctor = Doctor::create(['user_id'=>$doctorUser->id,'name'=>'Dr. Maya Chen','specialisation'=>'Family Medicine','phone'=>'03-5550 2101','email'=>'maya.chen@riverbend.test','availability_status'=>'Available']);
        $doctor2 = Doctor::create(['name'=>'Dr. Idris Salleh','specialisation'=>'Paediatrics','phone'=>'03-5550 2102','email'=>'idris.salleh@riverbend.test','availability_status'=>'Available']);
        $patients = collect([
            ['PT-2026-0001','Nadia Lim','1992-04-18','Female','012-555 0101','nadia.lim@example.test'],
            ['PT-2026-0002','Harish Kumar','1985-09-02','Male','012-555 0102','harish.kumar@example.test'],
            ['PT-2026-0003','Sofia Ahmad','2017-01-25','Female','012-555 0103','guardian.sofia@example.test'],
            ['PT-2026-0004','Ethan Wong','1978-11-30','Male','012-555 0104',null],
        ])->map(fn($p)=>Patient::create(['patient_number'=>$p[0],'full_name'=>$p[1],'date_of_birth'=>$p[2],'gender'=>$p[3],'phone'=>$p[4],'email'=>$p[5],'address'=>'Fictional address, Kuala Lumpur','emergency_contact'=>'Fictional Contact · 012-555 0199']));
        Appointment::create(['patient_id'=>$patients[0]->id,'doctor_id'=>$doctor->id,'appointment_date'=>today(),'start_time'=>'09:00','end_time'=>'09:30','reason'=>'Routine follow-up','status'=>'Checked In','created_by'=>$receptionist->id]);
        Appointment::create(['patient_id'=>$patients[1]->id,'doctor_id'=>$doctor->id,'appointment_date'=>today(),'start_time'=>'10:00','end_time'=>'10:30','reason'=>'General consultation','status'=>'Scheduled','created_by'=>$receptionist->id]);
        Appointment::create(['patient_id'=>$patients[2]->id,'doctor_id'=>$doctor2->id,'appointment_date'=>today(),'start_time'=>'11:00','end_time'=>'11:30','reason'=>'Wellness review','status'=>'Completed','created_by'=>$admin->id]);
        IssueReport::create(['reported_by'=>$receptionist->id,'title'=>'Waiting room display refresh delay','description'=>'The fictional queue display occasionally refreshes slowly in the demo environment.','priority'=>'Medium','status'=>'Open']);
    }
}
