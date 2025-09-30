<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportVote>
 */
class ReportVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_id' => \App\Models\Report::factory(),
            'user_id' => \App\Models\User::factory(),
            'vote_type' => fake()->randomElement(['upvote', 'downvote'], [80, 20]), // 80% upvotes
        ];
    }

    /**
     * Create an upvote.
     */
    public function upvote(): static
    {
        return $this->state(fn (array $attributes) => [
            'vote_type' => 'upvote',
        ]);
    }

    /**
     * Create a downvote.
     */
    public function downvote(): static
    {
        return $this->state(fn (array $attributes) => [
            'vote_type' => 'downvote',
        ]);
    }

    /**
     * Create a vote for a specific report and user combination.
     */
    public function forReportAndUser(int $reportId, int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'report_id' => $reportId,
            'user_id' => $userId,
        ]);
    }
}
