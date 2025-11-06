<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'schedule_slot_id' => \App\Models\ScheduleSlot::factory(),
            'confirmed_at' => fake()->optional()->dateTime(),
            'checked_in_at' => fake()->optional()->dateTime(),
            'checked_out_at' => fake()->optional()->dateTime(),
            'attended' => fake()->boolean(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
