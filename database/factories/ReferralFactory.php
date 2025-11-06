<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'referrer_id' => \App\Models\User::factory(),
            'referred_id' => \App\Models\User::factory(),
            'code' => strtoupper(fake()->unique()->bothify('????####')),
            'status' => 'pending',
            'reward_points' => 0,
            'rewarded_at' => null,
        ];
    }
}
