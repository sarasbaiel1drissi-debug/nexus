<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DailyTask;
use App\Models\UserDailyTask;
use App\Services\TaskAutoCompleteService;
use Carbon\Carbon;

class TasksController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if user already has tasks for today
        $todaysTasks = UserDailyTask::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->with('dailyTask')
            ->get();

        // If no tasks assigned today, pick 5 random ones
        if ($todaysTasks->isEmpty()) {
            $randomTasks = DailyTask::inRandomOrder()->take(5)->get();

            foreach ($randomTasks as $task) {
                UserDailyTask::create([
                    'user_id' => $user->id,
                    'daily_task_id' => $task->id,
                    'assigned_date' => $today,
                    'completed' => false,
                ]);
            }

            $todaysTasks = UserDailyTask::where('user_id', $user->id)
                ->where('assigned_date', $today)
                ->with('dailyTask')
                ->get();

            // Auto-complete "login" task if assigned today
            app(TaskAutoCompleteService::class)->checkLogin($user);

            // Refresh after potential login auto-complete
            $todaysTasks = UserDailyTask::where('user_id', $user->id)
                ->where('assigned_date', $today)
                ->with('dailyTask')
                ->get();
        }

        $completedCount = $todaysTasks->where('completed', true)->count();
        $totalCount = $todaysTasks->count();

        return view('tasks', [
            'tasks' => $todaysTasks,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'userXp' => $user->xp,
        ]);
    }

    public function toggle(UserDailyTask $task)
    {
        if ($task->user_id !== Auth::id()) abort(403);

        // Don't allow manually toggling auto-completed tasks
        if ($task->dailyTask->auto_complete_rule === 'all_others_done') {
            return back();
        }

        // Don't allow manually unchecking auto-completed tasks
        if ($task->completed && $task->dailyTask->auto_complete_rule !== null) {
            return back();
        }

        $task->update(['completed' => !$task->completed]);

        // Handle XP and cascading auto-completion
        $user = Auth::user();
        app(TaskAutoCompleteService::class)->handleManualToggle($task->fresh(), $user);

        return back();
    }
}
