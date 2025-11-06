<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reporter_id' => \App\Models\User::factory(),
            'reportable_type' => \App\Models\User::class,
            'reportable_id' => \App\Models\User::factory(),
            'reason' => fake()->randomElement(['spam', 'harassment', 'inappropriate', 'other']),
            'description' => fake()->paragraph(),
            'status' => 'pending',
            'reviewed_at' => null,
            'reviewed_by' => null,
        ];
    }
}
