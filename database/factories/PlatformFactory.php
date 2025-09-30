<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Platform>
 */
class PlatformFactory extends Factory
{
    /**
     * Common platforms used in Algeria.
     */
    protected static array $platforms = [
        'Facebook',
        'Instagram',
        'WhatsApp',
        'Telegram',
        'TikTok',
        'LinkedIn',
        'Twitter/X',
        'YouTube',
        'Snapchat',
        'Discord',
        'Viber',
        'Signal',
        'Email',
        'SMS',
        'Phone Call',
        'Website',
        'OLX',
        'Ouedkniss',
        'Other',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(static::$platforms),
            'is_active' => fake()->boolean(95), // 95% active platforms
        ];
    }

    /**
     * Create a specific platform by name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Create an inactive platform.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
