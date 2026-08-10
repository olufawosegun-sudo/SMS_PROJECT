<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicSessionFactory extends Factory
{
    protected $model = AcademicSession::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2030);

        return [
            'school_id' => School::factory(),
            'name' => $year.'/'.($year + 1),
            'start_date' => $year.'-09-01',
            'end_date' => ($year + 1).'-07-31',
            'is_current' => true,
        ];
    }
}
