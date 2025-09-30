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
        $status = fake()->randomElement(['pending', 'verified', 'investigating', 'resolved', 'rejected']);
        $isModerated = in_array($status, ['verified', 'investigating', 'resolved', 'rejected']);
        
        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\ScamCategory::factory(),
            'platform_id' => fake()->optional(0.8)->randomElement(\App\Models\Platform::pluck('id')->toArray()) ?? \App\Models\Platform::factory(),
            
            // Report content
            'title' => fake()->sentence(6, true),
            'description' => fake()->paragraphs(3, true),
            
            // Scammer info
            'scammer_name' => fake()->optional(0.7)->name(),
            'scammer_phone' => fake()->optional(0.6)->regexify('(05|06|07)[0-9]{8}'), // Algerian phone format
            'scammer_social_handle' => fake()->optional(0.5)->userName(),
            'scammer_profile_url' => fake()->optional(0.4)->url(),
            'scammer_bank_identifier' => fake()->optional(0.3)->bothify('###########'), // RIP/CCP format
            
            'incident_date' => fake()->optional(0.8)->dateTimeBetween('-2 years', 'now'),
            
            // Status & moderation
            'status' => $status,
            'moderator_id' => $isModerated ? \App\Models\User::where('role', 'in', ['moderator', 'admin'])->inRandomOrder()->first()?->id : null,
            'moderator_notes' => $isModerated ? fake()->optional(0.6)->sentence() : null,
            'moderated_at' => $isModerated ? fake()->dateTimeBetween('-1 year', 'now') : null,
            
            // Stats
            'views_count' => fake()->numberBetween(0, 1000),
            'upvotes_count' => fake()->numberBetween(0, 100),
            'downvotes_count' => fake()->numberBetween(0, 20),
            
            // Meta
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Create a pending report.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'moderator_id' => null,
            'moderator_notes' => null,
            'moderated_at' => null,
        ]);
    }

    /**
     * Create a verified report.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'verified',
            'moderator_id' => \App\Models\User::whereIn('role', ['moderator', 'admin'])->inRandomOrder()->first()?->id,
            'moderator_notes' => fake()->optional(0.7)->sentence(),
            'moderated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Create a rejected report.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'moderator_id' => \App\Models\User::whereIn('role', ['moderator', 'admin'])->inRandomOrder()->first()?->id,
            'moderator_notes' => fake()->sentence(),
            'moderated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Create a report with complete scammer info.
     */
    public function withCompleteScammerInfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'scammer_name' => fake()->name(),
            'scammer_phone' => fake()->regexify('(05|06|07)[0-9]{8}'),
            'scammer_social_handle' => fake()->userName(),
            'scammer_profile_url' => fake()->url(),
            'scammer_bank_identifier' => fake()->bothify('###########'),
        ]);
    }

    /**
     * Create a high-engagement report.
     */
    public function highEngagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(500, 5000),
            'upvotes_count' => fake()->numberBetween(50, 500),
            'downvotes_count' => fake()->numberBetween(0, 50),
        ]);
    }
}
