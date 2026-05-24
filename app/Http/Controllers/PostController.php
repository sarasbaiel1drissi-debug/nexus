<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Like;
use App\Models\Comment;
use App\Services\TaskAutoCompleteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:5000',
            'privacy' => 'required|in:public,followers',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Create the post
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'privacy' => $request->input('privacy', 'public')
        ]);
        
        // Handle media uploads if any
        if ($request->hasFile('media')) {
            $order = 0;
            
            foreach ($request->file('media') as $file) {
                $order++;
                
                // Store the file
                $path = $file->store('posts/' . date('Y/m'), 'public');
                
                // Determine file type
                $mimeType = $file->getMimeType();
                $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'video';
                
                // Get image dimensions if it's an image
                $metadata = [];
                if ($fileType === 'image') {
                    list($width, $height) = getimagesize($file->getPathname());
                    $metadata = ['width' => $width, 'height' => $height];
                }
                
                // Create media record
                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'mime_type' => $mimeType,
                    'file_size' => $file->getSize(),
                    'order' => $order,
                    'metadata' => $metadata
                ]);
            }
        }
        
        // Auto-complete post tasks
        app(TaskAutoCompleteService::class)->check(Auth::user(), 'post');

        return back()->with('success', 'Post created successfully!');
    }
    
    public function destroy($id)
    {
        $post = Post::visibleTo(Auth::user())->find($id);
        
        if (!$post) {
            return back()->with('error', 'Post not found');
        }
        
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }
        
        // Delete media files from storage
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        // Delete the post
        $post->delete();
        
        return back()->with('success', 'Post deleted successfully');
    }
    
    public function like($id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        
        // Check if already liked
        $existingLike = Like::where('user_id', Auth::id())
                            ->where('post_id', $id)
                            ->first();
        
        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            // Like
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $id
            ]);
            $post->increment('likes_count');
            $liked = true;

            // Auto-complete like tasks
            app(TaskAutoCompleteService::class)->check(Auth::user(), 'like');
        }

        $post->forceFill(['likes_count' => $post->likes()->count()])->save();
        
        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes_count
        ]);
    }
    
    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $post = Post::visibleTo(Auth::user())->find($id);
        
        if (!$post) {
            return back()->with('error', 'Post not found');
        }
        
        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $id,
            'content' => $request->input('content')
        ]);
        
        $post->increment('comments_count');
        $post->forceFill(['comments_count' => $post->comments()->count()])->save();

        // Auto-complete comment tasks
        app(TaskAutoCompleteService::class)->check(Auth::user(), 'comment');
        
        return back()->with('success', 'Comment added');
    }
}
