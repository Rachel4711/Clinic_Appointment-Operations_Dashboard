<?php
namespace Database\Factories;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
class PatientFactory extends Factory { protected $model=Patient::class; public function definition(){return ['patient_number'=>'PT-'.$this->faker->unique()->numerify('#######'),'full_name'=>$this->faker->name,'date_of_birth'=>$this->faker->dateTimeBetween('-80 years','-1 year')->format('Y-m-d'),'gender'=>$this->faker->randomElement(['Female','Male','Other','Prefer not to say']),'phone'=>$this->faker->numerify('01#-### ####'),'email'=>$this->faker->optional()->safeEmail,'address'=>$this->faker->address,'emergency_contact'=>$this->faker->name.' · '.$this->faker->phoneNumber];}}
