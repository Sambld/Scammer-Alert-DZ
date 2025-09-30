<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyStat>
 */
class DailyStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportsCount = fake()->numberBetween(0, 50);
        $verifiedCount = fake()->numberBetween(0, (int)($reportsCount * 0.7)); // Max 70% verified
        $newUsersCount = fake()->numberBetween(0, 20);
        
        return [
            'date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'reports_count' => $reportsCount,
            'verified_reports_count' => $verifiedCount,
            'new_users_count' => $newUsersCount,
            'total_amount_lost' => fake()->randomFloat(2, 0, 1000000), // Up to 1M DZD
        ];
    }

    /**
     * Create stats for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date,
        ]);
    }

    /**
     * Create high activity day stats.
     */
    public function highActivity(): static
    {
        $reportsCount = fake()->numberBetween(30, 100);
        return $this->state(fn (array $attributes) => [
            'reports_count' => $reportsCount,
            'verified_reports_count' => fake()->numberBetween((int)($reportsCount * 0.5), (int)($reportsCount * 0.8)),
            'new_users_count' => fake()->numberBetween(10, 50),
            'total_amount_lost' => fake()->randomFloat(2, 50000, 2000000),
        ]);
    }

    /**
     * Create low activity day stats.
     */
    public function lowActivity(): static
    {
        return $this->state(fn (array $attributes) => [
            'reports_count' => fake()->numberBetween(0, 5),
            'verified_reports_count' => fake()->numberBetween(0, 3),
            'new_users_count' => fake()->numberBetween(0, 3),
            'total_amount_lost' => fake()->randomFloat(2, 0, 10000),
        ]);
    }
}
