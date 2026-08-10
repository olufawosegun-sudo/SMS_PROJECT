<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => fake()->randomElement(['Owner', 'Principal', 'Teacher', 'Bursar', 'Parent', 'Student']),
            'description' => fake()->sentence(),
        ];
    }
}
