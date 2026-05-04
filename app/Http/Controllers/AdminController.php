<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Map of table names to their Eloquent models (for fillable-aware operations).
     */
    protected array $modelMap = [
        'users'            => \App\Models\User::class,
        'badges'           => \App\Models\Badge::class,
        'badge_user'       => null, // pivot – use DB
        'posts'            => \App\Models\Post::class,
        'post_media'       => \App\Models\PostMedia::class,
        'comments'         => \App\Models\Comment::class,
        'likes'            => \App\Models\Like::class,
        'games'            => \App\Models\Game::class,
        'game_user'        => null,
        'conversations'    => \App\Models\Conversation::class,
        'messages'         => \App\Models\Message::class,
        'follows'          => \App\Models\Follow::class,
        'uplifts'          => \App\Models\Uplift::class,
        'notifications'    => \App\Models\Notification::class,
        'daily_tasks'      => \App\Models\DailyTask::class,
        'user_daily_tasks' => \App\Models\UserDailyTask::class,
    ];

    /**
     * Columns to hide from forms (auto-managed).
     */
    protected array $hiddenColumns = [
        'id', 'created_at', 'updated_at', 'deleted_at', 'remember_token',
        'email_verified_at', 'password',
    ];

    /**
     * Admin dashboard with stats.
     */
    public function index()
    {
        $stats = [
            'users' => DB::table('users')->count(),
            'posts' => DB::table('posts')->count(),
            'badges' => DB::table('badges')->count(),
            'daily_tasks' => DB::table('daily_tasks')->count(),
        ];

        $recentUsers = \App\Models\User::orderByDesc('created_at')->limit(5)->get();

        $tableCounts = [];
        foreach (array_keys($this->modelMap) as $t) {
            $tableCounts[$t] = DB::table($t)->count();
        }

        return view('admin.index', [
            'tables' => array_keys($this->modelMap),
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'tableCounts' => $tableCounts,
        ]);
    }

    /**
     * Show paginated table data.
     */
    public function table(string $table)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);
        $records = DB::table($table)->orderByDesc('id')->paginate(20);

        return view('admin.table', [
            'tables'       => array_keys($this->modelMap),
            'currentTable' => $table,
            'columns'      => $columns,
            'editableColumns' => array_values(array_diff($columns, $this->hiddenColumns)),
            'records'      => $records,
        ]);
    }

    /**
     * Create a new record.
     */
    public function store(Request $request, string $table)
    {
        $this->validateTable($table);

        $columns = array_diff(
            Schema::getColumnListing($table),
            $this->hiddenColumns
        );

        $data = $request->only($columns);

        // Hash password if present
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        // Remove empty values
        $data = array_filter($data, fn($v) => $v !== null && $v !== '');

        // Set timestamps if they exist
        if (in_array('created_at', Schema::getColumnListing($table))) {
            $data['created_at'] = now();
        }
        if (in_array('updated_at', Schema::getColumnListing($table))) {
            $data['updated_at'] = now();
        }

        DB::table($table)->insert($data);

        return back()->with('success', 'Record created successfully.');
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request, string $table, int $id)
    {
        $this->validateTable($table);

        $columns = array_diff(
            Schema::getColumnListing($table),
            $this->hiddenColumns
        );

        $data = $request->only($columns);

        // Hash password if present and not empty
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Remove empty strings for nullable fields (keep booleans/zeros)
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        if (in_array('updated_at', Schema::getColumnListing($table))) {
            $data['updated_at'] = now();
        }

        DB::table($table)->where('id', $id)->update($data);

        return back()->with('success', 'Record updated successfully.');
    }

    /**
     * Delete a record.
     */
    public function destroy(string $table, int $id)
    {
        $this->validateTable($table);

        // Soft-delete if the table has deleted_at, otherwise hard delete
        if (in_array('deleted_at', Schema::getColumnListing($table))) {
            DB::table($table)->where('id', $id)->update(['deleted_at' => now()]);
        } else {
            DB::table($table)->where('id', $id)->delete();
        }

        return back()->with('success', 'Record deleted.');
    }

    /**
     * Ensure the requested table is in the allowed list.
     */
    protected function validateTable(string $table): void
    {
        if (!array_key_exists($table, $this->modelMap)) {
            abort(404, 'Table not found.');
        }
    }
}
