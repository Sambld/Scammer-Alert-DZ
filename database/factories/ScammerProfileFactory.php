<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScammerProfile>
 */
class ScammerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->optional(0.7)->regexify('(05|06|07)[0-9]{8}'), // Algerian phone format
            'social_handle' => fake()->optional(0.6)->userName(),
            'name' => fake()->optional(0.8)->name(),
            'bank_identifier' => fake()->optional(0.4)->bothify('###########'), // RIP/CCP format
            'reports_count' => fake()->numberBetween(1, 10),
            'last_reported_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a high-threat scammer profile.
     */
    public function highThreat(): static
    {
        return $this->state(fn (array $attributes) => [
            'reports_count' => fake()->numberBetween(10, 50),
            'last_reported_at' => fake()->dateTimeBetween('-1 month', 'now'),
            // More complete info for high-threat scammers
            'phone' => fake()->regexify('(05|06|07)[0-9]{8}'),
            'social_handle' => fake()->userName(),
            'name' => fake()->name(),
            'bank_identifier' => fake()->bothify('###########'),
        ]);
    }

    /**
     * Create a scammer with complete information.
     */
    public function withCompleteInfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => fake()->regexify('(05|06|07)[0-9]{8}'),
            'social_handle' => fake()->userName(),
            'name' => fake()->name(),
            'bank_identifier' => fake()->bothify('###########'),
        ]);
    }

    /**
     * Create a recently active scammer.
     */
    public function recentlyActive(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_reported_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'reports_count' => fake()->numberBetween(2, 8),
        ]);
    }

    /**
     * Create a scammer with specific phone number.
     */
    public function withPhone(string $phone): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $phone,
        ]);
    }

    /**
     * Create a scammer with specific social handle.
     */
    public function withSocialHandle(string $handle): static
    {
        return $this->state(fn (array $attributes) => [
            'social_handle' => $handle,
        ]);
    }
}
