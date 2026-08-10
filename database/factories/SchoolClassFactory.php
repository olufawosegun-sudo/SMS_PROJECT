<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => fake()->randomElement(['SS 1', 'SS 2', 'SS 3', 'JSS 1', 'JSS 2', 'JSS 3']),
            'level' => 'Secondary',
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
