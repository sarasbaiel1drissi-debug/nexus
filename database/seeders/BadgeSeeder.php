<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'New User',
                'description' => 'Welcome to Nexus! Awarded when you join the platform.',
                'icon' => 'feather-star',
                'criteria' => ['type' => 'registration'],
            ],
            [
                'name' => 'Rookie',
                'description' => 'Earned 100 XP from completing tasks.',
                'icon' => 'feather-zap',
                'criteria' => ['type' => 'xp', 'threshold' => 100],
            ],
            [
                'name' => 'Rising Star',
                'description' => 'Earned 500 XP — you\'re on fire!',
                'icon' => 'feather-trending-up',
                'criteria' => ['type' => 'xp', 'threshold' => 500],
            ],
            [
                'name' => 'Veteran',
                'description' => 'Earned 1,000 XP. A true dedicated gamer.',
                'icon' => 'feather-award',
                'criteria' => ['type' => 'xp', 'threshold' => 1000],
            ],
            [
                'name' => 'Legend',
                'description' => 'Earned 5,000 XP. You are a Nexus legend!',
                'icon' => 'feather-shield',
                'criteria' => ['type' => 'xp', 'threshold' => 5000],
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }
}
