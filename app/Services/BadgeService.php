<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;

class BadgeService
{
    /**
     * Award the "New User" badge to a freshly registered user.
     */
    public function awardNewUserBadge(User $user): void
    {
        $badge = Badge::where('name', 'New User')->first();

        if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, [
                'earned_at' => now(),
                'expires_at' => now()->addDays(10),
            ]);
        }
    }

    /**
     * Check all XP-threshold badges and award any the user qualifies for.
     */
    public function checkAndAwardXpBadges(User $user): void
    {
        $xpBadges = Badge::whereNotNull('criteria')
            ->get()
            ->filter(function ($badge) {
                $criteria = $badge->criteria;
                return is_array($criteria) && ($criteria['type'] ?? null) === 'xp';
            });

        foreach ($xpBadges as $badge) {
            $threshold = $badge->criteria['threshold'] ?? 0;

            if ($user->xp >= $threshold && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id, [
                    'earned_at' => now(),
                    'expires_at' => null,
                ]);
            }
        }
    }
    
}
