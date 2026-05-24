<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

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
        'audience_lists'   => \App\Models\AudienceList::class,
        'audience_list_members' => \App\Models\AudienceListMember::class,
    ];

    /**
     * Columns to hide from forms (auto-managed).
     */
    protected array $hiddenColumns = [
        'id', 'created_at', 'updated_at', 'deleted_at', 'remember_token',
        'email_verified_at',
    ];

    protected array $booleanColumns = [
        'is_admin', 'completed', 'is_online', 'read',
    ];

    protected array $jsonColumns = [
        'criteria', 'metadata', 'playstyle_tags', 'options',
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

        $columns = Schema::getColumnListing($table);
        $data = $this->formData($request, $columns, creating: true);

        try {
            DB::table($table)->insert($data);
        } catch (QueryException $exception) {
            return back()
                ->withInput()
                ->withErrors(['admin' => $this->databaseErrorMessage($exception)]);
        }

        return back()->with('success', 'Record created successfully.');
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request, string $table, int $id)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);
        $data = $this->formData($request, $columns, creating: false);

        try {
            DB::table($table)->where('id', $id)->update($data);
        } catch (QueryException $exception) {
            return back()
                ->withInput()
                ->withErrors(['admin' => $this->databaseErrorMessage($exception)]);
        }

        return back()->with('success', 'Record updated successfully.');
    }

    /**
     * Delete a record.
     */
    public function destroy(string $table, int $id)
    {
        $this->validateTable($table);

        try {
            // Soft-delete if the table has deleted_at, otherwise hard delete.
            if (Schema::hasColumn($table, 'deleted_at')) {
                DB::table($table)->where('id', $id)->update(['deleted_at' => now()]);
            } else {
                DB::table($table)->where('id', $id)->delete();
            }
        } catch (QueryException $exception) {
            return back()->withErrors(['admin' => $this->databaseErrorMessage($exception)]);
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

    protected function formData(Request $request, array $columns, bool $creating): array
    {
        $editableColumns = array_values(array_diff($columns, $this->hiddenColumns));
        $data = $request->only($editableColumns);

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                if ($value === null || $value === '') {
                    unset($data[$key]);
                    continue;
                }

                $data[$key] = Hash::make($value);
                continue;
            }

            if (in_array($key, $this->booleanColumns, true)) {
                $data[$key] = (bool) $value;
                continue;
            }

            if (in_array($key, $this->jsonColumns, true)) {
                $data[$key] = $this->jsonValue($value);
                continue;
            }

            if ($value === '') {
                $data[$key] = null;
            }
        }

        if ($creating && in_array('created_at', $columns, true)) {
            $data['created_at'] = now();
        }

        if (in_array('updated_at', $columns, true)) {
            $data['updated_at'] = now();
        }

        return $data;
    }

    protected function jsonValue(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded);
            }

            return json_encode(array_values(array_filter(array_map('trim', explode(',', $value)))));
        }

        return json_encode($value);
    }

    protected function databaseErrorMessage(QueryException $exception): string
    {
        return $exception->errorInfo[2] ?? $exception->getMessage();
    }
}
