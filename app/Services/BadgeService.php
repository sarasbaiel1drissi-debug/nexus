<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Award the "New User" badge to a freshly registered user.
     */
    public function awardNewUserBadge(User $user): void
    {
        $badge = Badge::where('name', 'New User')->first();

        if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
            DB::transaction(function () use ($user, $badge) {
                $user->badges()->attach($badge->id, [
                    'earned_at' => now(),
                    'expires_at' => now()->addDays(10),
                ]);

                $this->notifyBadgeAwarded($user, $badge);
            });
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
                DB::transaction(function () use ($user, $badge) {
                    $user->badges()->attach($badge->id, [
                        'earned_at' => now(),
                        'expires_at' => null,
                    ]);

                    $this->notifyBadgeAwarded($user, $badge);
                });
            }
        }
    }

    private function notifyBadgeAwarded(User $user, Badge $badge): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'badge',
            'from_user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
    }
}
