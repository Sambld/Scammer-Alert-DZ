<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportComment>
 */
class ReportCommentFactory extends Factory
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
            'parent_id' => null, // Top-level comment by default
            'content' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'is_from_victim' => fake()->boolean(20), // 20% chance of being from victim
            'status' => fake()->randomElement(['active', 'hidden', 'deleted'], [85, 10, 5]), // 85% active
        ];
    }

    /**
     * Create a reply to a parent comment.
     */
    public function reply(?int $parentId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId ?? \App\Models\ReportComment::factory()->create()->id,
            'content' => fake()->sentences(fake()->numberBetween(1, 2), true),
        ]);
    }

    /**
     * Create a comment from a victim.
     */
    public function fromVictim(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_from_victim' => true,
            'content' => fake()->randomElement([
                'Same thing happened to me with this scammer!',
                'I also lost money to this person. Thank you for reporting.',
                'This scammer contacted me too but I didn\'t fall for it.',
                'I can confirm this is a scammer. They tried the same trick on me.',
            ]) . ' ' . fake()->sentence(),
        ]);
    }

    /**
     * Create a hidden comment.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hidden',
        ]);
    }

    /**
     * Create a deleted comment.
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'deleted',
            'content' => '[Comment deleted]',
        ]);
    }
}
