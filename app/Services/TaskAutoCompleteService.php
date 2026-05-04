<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDailyTask;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Message;
use App\Models\Uplift;
use Carbon\Carbon;

class TaskAutoCompleteService
{
    /**
     * Check and auto-complete tasks matching the given action type.
     *
     * Called after a user performs a platform action (follow, like, post, etc.)
     * Looks at today's assigned tasks with matching auto_complete_rule and
     * checks if the user has met the threshold.
     */
    public function check(User $user, string $actionType): void
    {
        $today = Carbon::today();

        // Get today's uncompleted tasks that match this action type
        $tasks = UserDailyTask::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->where('completed', false)
            ->with('dailyTask')
            ->get()
            ->filter(function ($userTask) use ($actionType) {
                $rule = $userTask->dailyTask->auto_complete_rule;
                if (!$rule) return false;

                // Match rules like "follow:3" or "login"
                $ruleParts = explode(':', $rule);
                return $ruleParts[0] === $actionType;
            });

        foreach ($tasks as $userTask) {
            $rule = $userTask->dailyTask->auto_complete_rule;
            $ruleParts = explode(':', $rule);
            $ruleType = $ruleParts[0];
            $threshold = isset($ruleParts[1]) ? (int) $ruleParts[1] : 1;

            $count = $this->countTodayActions($user, $ruleType, $today);

            if ($count >= $threshold) {
                $userTask->update(['completed' => true]);
                $this->awardXp($user, $userTask->dailyTask->xp);
            }
        }

        // Always check if "all_others_done" should be completed
        $this->checkAllOthersDone($user);
    }

    /**
     * Auto-complete the "login" task on user login.
     */
    public function checkLogin(User $user): void
    {
        $today = Carbon::today();

        $loginTask = UserDailyTask::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->where('completed', false)
            ->whereHas('dailyTask', function ($q) {
                $q->where('auto_complete_rule', 'login');
            })
            ->with('dailyTask')
            ->first();

        if ($loginTask) {
            $loginTask->update(['completed' => true]);
            $this->awardXp($user, $loginTask->dailyTask->xp);
        }

        $this->checkAllOthersDone($user);
    }

    /**
     * Check if ALL other daily tasks are done, then auto-complete
     * the "all_others_done" meta-task.
     */
    public function checkAllOthersDone(User $user): void
    {
        $today = Carbon::today();

        $todaysTasks = UserDailyTask::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->with('dailyTask')
            ->get();

        // Find the "all_others_done" task
        $metaTask = $todaysTasks->first(function ($t) {
            return $t->dailyTask->auto_complete_rule === 'all_others_done';
        });

        if (!$metaTask) return;

        // Check all OTHER tasks (not the meta-task itself)
        $otherTasks = $todaysTasks->filter(function ($t) {
            return $t->dailyTask->auto_complete_rule !== 'all_others_done';
        });

        $allDone = $otherTasks->every(fn($t) => $t->completed);

        if ($allDone && !$metaTask->completed) {
            $metaTask->update(['completed' => true]);
            $this->awardXp($user, $metaTask->dailyTask->xp);
        } elseif (!$allDone && $metaTask->completed) {
            // If someone unchecked a task, un-complete the meta-task
            $metaTask->update(['completed' => false]);
            $this->deductXp($user, $metaTask->dailyTask->xp);
        }
    }

    /**
     * Handle manual toggle of a task (for tasks without an auto_complete_rule).
     * Awards or deducts XP based on whether the task was completed or uncompleted.
     */
    public function handleManualToggle(UserDailyTask $task, User $user): void
    {
        if ($task->completed) {
            // Task was just completed
            $this->awardXp($user, $task->dailyTask->xp);
        } else {
            // Task was uncompleted
            $this->deductXp($user, $task->dailyTask->xp);
        }

        $this->checkAllOthersDone($user);
    }

    /**
     * Count how many times the user performed a specific action today.
     */
    private function countTodayActions(User $user, string $actionType, Carbon $today): int
    {
        return match ($actionType) {
            'follow' => Follow::where('follower_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            'like' => Like::where('user_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            'comment' => Comment::where('user_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            'post' => Post::where('user_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            'message' => Message::where('sender_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            'uplift' => Uplift::where('uplifter_id', $user->id)
                ->whereDate('created_at', $today)->count(),

            default => 0,
        };
    }

    private function awardXp(User $user, int $amount): void
    {
        $user->increment('xp', $amount);
        app(BadgeService::class)->checkAndAwardXpBadges($user->fresh());
    }

    private function deductXp(User $user, int $amount): void
    {
        $newXp = max(0, $user->xp - $amount);
        $user->update(['xp' => $newXp]);
    }
}
