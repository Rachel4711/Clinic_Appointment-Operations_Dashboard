<?php
namespace Database\Factories;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
class DoctorFactory extends Factory { protected $model=Doctor::class; public function definition(){return ['name'=>'Dr. '.$this->faker->name,'specialisation'=>$this->faker->randomElement(['Family Medicine','Paediatrics','Dermatology']),'phone'=>$this->faker->phoneNumber,'email'=>$this->faker->unique()->safeEmail,'availability_status'=>'Available'];}}
