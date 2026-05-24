<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Uplift;
use App\Models\Notification;
use App\Models\User;
use App\Models\AudienceList;
use App\Models\AudienceListMember;
use App\Services\TaskAutoCompleteService;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function follow(User $user)
    {
        if (auth()->id() === $user->id) abort(403);

        $exists = Follow::where('follower_id', auth()->id())->where('following_id', $user->id)->first();

        if ($exists) {
            $exists->delete();
            return back()->with('status', 'Unfollowed ' . $user->name);
        }

        Follow::create(['follower_id' => auth()->id(), 'following_id' => $user->id]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'follow',
            'from_user_id' => auth()->id(),
        ]);

        // Auto-complete follow tasks
        app(TaskAutoCompleteService::class)->check(auth()->user(), 'follow');

        return back()->with('status', 'Following ' . $user->name);
    }

    public function uplift(User $user)
    {
        if (auth()->id() === $user->id) abort(403);

        $exists = Uplift::where('uplifter_id', auth()->id())->where('uplifted_id', $user->id)->first();

        if ($exists) {
            $exists->delete();
            return back()->with('status', 'Removed uplift');
        }

        Uplift::create(['uplifter_id' => auth()->id(), 'uplifted_id' => $user->id]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'uplift',
            'from_user_id' => auth()->id(),
        ]);

        // Auto-complete uplift tasks
        app(TaskAutoCompleteService::class)->check(auth()->user(), 'uplift');

        return back()->with('status', 'Uplifted ' . $user->name);
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->with(['fromUser', 'badge'])
            ->latest()
            ->paginate(20);

        // Mark all as read
        Notification::where('user_id', auth()->id())->where('read', false)->update(['read' => true]);

        return view('notifications', compact('notifications'));
    }

    public function connections()
    {
        $user = auth()->user();

        // IDs of users I follow
        $followingIds = Follow::where('follower_id', $user->id)->pluck('following_id');
        // IDs of users who follow me
        $followerIds = Follow::where('following_id', $user->id)->pluck('follower_id');
        // Mutual = intersection
        $mutualIds = $followingIds->intersect($followerIds);

        $following = User::whereIn('id', $followingIds)->get();
        $followers = User::whereIn('id', $followerIds)->get();
        $mutuals = User::whereIn('id', $mutualIds)->get();
        
        $customLists = AudienceList::where('user_id', $user->id)->with('members.user')->get();

        return view('audience', compact('following', 'followers', 'mutuals', 'customLists'));
    }

    public function createList(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        AudienceList::create([
            'user_id' => auth()->id(),
            'name' => $request->name
        ]);
        return back()->with('status', 'List created successfully!');
    }

    public function deleteList(AudienceList $list)
    {
        if ($list->user_id !== auth()->id()) abort(403);
        $list->delete();
        return back()->with('status', 'List deleted!');
    }

    public function addToList(AudienceList $list, User $user)
    {
        if ($list->user_id !== auth()->id()) abort(403);
        AudienceListMember::firstOrCreate([
            'audience_list_id' => $list->id,
            'user_id' => $user->id
        ]);
        return back()->with('status', 'User added to list!');
    }

    public function removeFromList(AudienceList $list, User $user)
    {
        if ($list->user_id !== auth()->id()) abort(403);
        AudienceListMember::where('audience_list_id', $list->id)->where('user_id', $user->id)->delete();
        return back()->with('status', 'User removed from list!');
    }
}
