<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
})->name('home');

Route::get('/dashboard', [FeedController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', AdminUserController::class);
Route::resource('badges', BadgeController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/find-players', function () {
        return view('find-players');
    })->name('find-players');
    
    Route::get('/messages/{userId?}', [ChatController::class, 'index'])->name('messages');
    Route::get('/chat/conversations', [ChatController::class, 'conversations'])->name('chat.conversations');
    Route::post('/chat/open/{user}', [ChatController::class, 'open'])->name('chat.open');
    Route::get('/chat/conversations/{conversation}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/conversations/{conversation}/read', [ChatController::class, 'read'])->name('chat.read');
    Route::get('/chat/conversations/{conversation}/stream', [ChatController::class, 'stream'])->name('chat.stream');

    Route::post('/follow/{user}', [SocialController::class, 'follow'])->name('follow');
    Route::post('/uplift/{user}', [SocialController::class, 'uplift'])->name('uplift');
    Route::get('/notifications', [SocialController::class, 'notifications'])->name('notifications');

    // Audience / Connections
    Route::get('/connections', [SocialController::class, 'connections'])->name('connections');
    Route::post('/audience-lists', [SocialController::class, 'createList'])->name('audience.lists.create');
    Route::delete('/audience-lists/{list}', [SocialController::class, 'deleteList'])->name('audience.lists.destroy');
    Route::post('/audience-lists/{list}/add/{user}', [SocialController::class, 'addToList'])->name('audience.lists.add');
    Route::delete('/audience-lists/{list}/remove/{user}', [SocialController::class, 'removeFromList'])->name('audience.lists.remove');

    // Daily Tasks
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks');
    Route::patch('/tasks/{task}/toggle', [TasksController::class, 'toggle'])->name('tasks.toggle');
});

Route::get('/u/{username}', [UserProfileController::class, 'show'])->name('user.profile');

// Post routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
});

// Admin routes (admin users only)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/{table}', [AdminController::class, 'table'])->name('admin.table');
    Route::post('/{table}', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/{table}/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{table}/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

require __DIR__.'/auth.php';
