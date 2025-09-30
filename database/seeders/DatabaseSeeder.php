<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting EscrocAlertDz database seeding...');

        // 1. Create admin and moderator users first
        $this->seedAdminUsers();
        
        // 2. Create regular users
        $this->seedUsers();
        
        // 3. Create categories (needed for reports)
        $this->seedCategories();
        
        // 4. Create platforms (needed for reports)
        $this->seedPlatforms();
        
        // 5. Create reports (depends on users, categories, platforms)
        $this->seedReports();
        
        // 6. Create scammer profiles (can be independent or from reports)
        $this->seedScammerProfiles();
        
        // 7. Create report media (depends on reports)
        $this->seedReportMedia();
        
        // 8. Create report comments (depends on reports and users)
        $this->seedReportComments();
        
        // 9. Create report votes (depends on reports and users)
        $this->seedReportVotes();
        
        // 10. Create daily stats (can be independent)
        $this->seedDailyStats();
        
        $this->command->info('✅ Database seeding completed successfully!');
    }

    /**
     * Seed admin and moderator users.
     */
    private function seedAdminUsers(): void
    {
        $this->command->info('👑 Creating admin and moderator users...');
        
        // Create main admin
        \App\Models\User::factory()->admin()->create([
            'name' => 'System Administrator',
            'email' => 'admin@escrocalert.dz',
        ]);
        
        // Create test admin
        \App\Models\User::factory()->admin()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
        ]);
        
        // Create moderators
        \App\Models\User::factory(3)->moderator()->create();
        
        $this->command->info('✓ Created 1 main admin, 1 test admin, and 3 moderators');
    }

    /**
     * Seed regular users.
     */
    private function seedUsers(): void
    {
        $this->command->info('👥 Creating regular users...');
        
        // Create active users
        \App\Models\User::factory(50)->create();
        
        // Create some inactive users
        \App\Models\User::factory(5)->inactive()->create();
        
        // Create some unverified users
        \App\Models\User::factory(10)->unverified()->create();
        
        $this->command->info('✓ Created 65 users (50 active, 5 inactive, 10 unverified)');
    }

    /**
     * Seed scam categories.
     */
    private function seedCategories(): void
    {
        $this->command->info('📋 Creating scam categories...');
        
        $categories = [
            'Financial Fraud',
            'Online Shopping Scam',
            'Romance Scam',
            'Job Offer Scam',
            'Phishing',
            'Social Media Scam',
            'Identity Theft',
            'Other',
        ];
        
        foreach ($categories as $categoryName) {
            \App\Models\ScamCategory::factory()->withName($categoryName)->create();
        }
        
        // Create one inactive category
        \App\Models\ScamCategory::factory()->withName('Financial Fraud')->inactive()->create();
        
        $this->command->info('✓ Created 8 active categories and 1 inactive category');
    }

    /**
     * Seed platforms.
     */
    private function seedPlatforms(): void
    {
        $this->command->info('📱 Creating platforms...');
        
        $platforms = [
            'Facebook', 'Instagram', 'WhatsApp', 'Telegram', 'TikTok',
            'LinkedIn', 'Twitter/X', 'YouTube', 'Snapchat', 'Discord',
            'Email', 'SMS', 'Phone Call', 'Website', 'OLX', 'Ouedkniss'
        ];
        
        foreach ($platforms as $platformName) {
            \App\Models\Platform::factory()->withName($platformName)->create();
        }
        
        // Create inactive platforms
        \App\Models\Platform::factory(2)->inactive()->create();
        
        $this->command->info('✓ Created 16 active platforms and 2 inactive platforms');
    }

    /**
     * Seed reports.
     */
    private function seedReports(): void
    {
        $this->command->info('📄 Creating reports...');
        
        $users = \App\Models\User::where('role', 'user')->get();
        $categories = \App\Models\ScamCategory::where('is_active', true)->get();
        $platforms = \App\Models\Platform::where('is_active', true)->get();
        
        // Create verified reports
        \App\Models\Report::factory(30)
            ->verified()
            ->state(fn() => [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'platform_id' => $platforms->random()->id,
            ])
            ->create();
        
        // Create pending reports
        \App\Models\Report::factory(20)
            ->pending()
            ->state(fn() => [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'platform_id' => $platforms->random()->id,
            ])
            ->create();
        
        // Create some rejected reports
        \App\Models\Report::factory(5)
            ->rejected()
            ->state(fn() => [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'platform_id' => $platforms->random()->id,
            ])
            ->create();
        
        // Create high-engagement reports
        \App\Models\Report::factory(10)
            ->highEngagement()
            ->withCompleteScammerInfo()
            ->state(fn() => [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'platform_id' => $platforms->random()->id,
            ])
            ->create();
        
        $this->command->info('✓ Created 65 reports (30 verified, 20 pending, 5 rejected, 10 high-engagement)');
    }

    /**
     * Seed scammer profiles.
     */
    private function seedScammerProfiles(): void
    {
        $this->command->info('👹 Creating scammer profiles...');
        
        // Create high-threat scammers
        \App\Models\ScammerProfile::factory(5)->highThreat()->create();
        
        // Create regular scammers
        \App\Models\ScammerProfile::factory(15)->create();
        
        // Create some with complete info
        \App\Models\ScammerProfile::factory(8)->withCompleteInfo()->create();
        
        // Create recently active scammers
        \App\Models\ScammerProfile::factory(10)->recentlyActive()->create();
        
        $this->command->info('✓ Created 38 scammer profiles (5 high-threat, 15 regular, 8 complete info, 10 recently active)');
    }

    /**
     * Seed report media.
     */
    private function seedReportMedia(): void
    {
        $this->command->info('🖼️ Creating report media...');
        
        $reports = \App\Models\Report::all();
        
        // Add media to 60% of reports
        $reportsWithMedia = $reports->random((int)($reports->count() * 0.6));
        
        foreach ($reportsWithMedia as $report) {
            $mediaCount = fake()->numberBetween(1, 4);
            
            for ($i = 0; $i < $mediaCount; $i++) {
                \App\Models\ReportMedia::factory()
                    ->state(['report_id' => $report->id, 'sort_order' => $i])
                    ->create();
            }
        }
        
        $this->command->info('✓ Created media files for 60% of reports');
    }

    /**
     * Seed report comments.
     */
    private function seedReportComments(): void
    {
        $this->command->info('💬 Creating report comments...');
        
        $reports = \App\Models\Report::where('status', 'verified')->get();
        $users = \App\Models\User::all();
        
        $commentCount = 0;
        
        foreach ($reports as $report) {
            // 70% chance of having comments
            if (fake()->boolean(70)) {
                $numComments = fake()->numberBetween(1, 8);
                
                for ($i = 0; $i < $numComments; $i++) {
                    $comment = \App\Models\ReportComment::factory()
                        ->state([
                            'report_id' => $report->id,
                            'user_id' => $users->random()->id,
                        ])
                        ->create();
                    
                    $commentCount++;
                    
                    // 30% chance of having replies to this comment
                    if (fake()->boolean(30)) {
                        $numReplies = fake()->numberBetween(1, 3);
                        
                        for ($j = 0; $j < $numReplies; $j++) {
                            \App\Models\ReportComment::factory()
                                ->reply($comment->id)
                                ->state([
                                    'report_id' => $report->id,
                                    'user_id' => $users->random()->id,
                                ])
                                ->create();
                            
                            $commentCount++;
                        }
                    }
                }
                
                // Add some victim comments
                if (fake()->boolean(40)) {
                    \App\Models\ReportComment::factory()
                        ->fromVictim()
                        ->state([
                            'report_id' => $report->id,
                            'user_id' => $users->random()->id,
                        ])
                        ->create();
                    
                    $commentCount++;
                }
            }
        }
        
        $this->command->info("✓ Created {$commentCount} comments with replies and victim testimonies");
    }

    /**
     * Seed report votes.
     */
    private function seedReportVotes(): void
    {
        $this->command->info('👍 Creating report votes...');
        
        $reports = \App\Models\Report::where('status', 'verified')->get();
        $users = \App\Models\User::all();
        
        $voteCount = 0;
        
        foreach ($reports as $report) {
            // Random number of users who voted (30-80% of users)
            $votersCount = fake()->numberBetween((int)($users->count() * 0.3), (int)($users->count() * 0.8));
            $voters = $users->random($votersCount);
            
            foreach ($voters as $user) {
                try {
                    \App\Models\ReportVote::factory()
                        ->forReportAndUser($report->id, $user->id)
                        ->create();
                    
                    $voteCount++;
                } catch (\Exception $e) {
                    // Skip if duplicate (unique constraint)
                    continue;
                }
            }
        }
        
        $this->command->info("✓ Created {$voteCount} votes on verified reports");
    }

    /**
     * Seed daily statistics.
     */
    private function seedDailyStats(): void
    {
        $this->command->info('📊 Creating daily statistics...');
        
        // Create stats for the last 365 days
        $startDate = now()->subDays(365);
        $endDate = now();
        
        $current = $startDate->copy();
        $statsCount = 0;
        
        while ($current->lte($endDate)) {
            $dayType = fake()->randomElement(['low', 'normal', 'high'], [20, 60, 20]);
            
            $factory = \App\Models\DailyStat::factory()->forDate($current->toDateString());
            
            if ($dayType === 'high') {
                $factory = $factory->highActivity();
            } elseif ($dayType === 'low') {
                $factory = $factory->lowActivity();
            }
            
            $factory->create();
            $statsCount++;
            $current->addDay();
        }
        
        $this->command->info("✓ Created {$statsCount} days of statistical data");
    }
}
