<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'school_id' => School::factory(),
            'user_id' => User::factory(),
            'admission_no' => 'ADM/'.fake()->unique()->randomNumber(5, true),
            'class_id' => SchoolClass::factory(),
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ];
    }
}
