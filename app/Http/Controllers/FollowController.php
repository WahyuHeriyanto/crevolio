<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\FollowRelation;
use App\Models\FollowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function showList(User $user, $type)
    {
        
        if ($type === 'followers') {
            $userIds = FollowRelation::where('follow_user_id', $user->id)
                        ->pluck('user_id');
            $title = "Followers of " . $user->name;
        } else {
            $userIds = FollowRelation::where('user_id', $user->id)
                        ->pluck('follow_user_id');
            $title = "People followed by " . $user->name;
        }

        $users = User::whereIn('id', $userIds)
                    ->with(['profile.careerPosition', 'profile.expertises.expertise'])
                    ->paginate(20);

        return view('follow.list', compact('users', 'title', 'type', 'user'));
    }

    public function follow(User $user)
    {
        $authId = auth()->id();
        $authUser = auth()->user();
        $targetProfile = $user->profile;

        $alreadyFollowed = FollowRelation::where('user_id', $authId)->where('follow_user_id', $user->id)->exists();
        $alreadyRequested = FollowRequest::where('requester_id', $authId)->where('user_id', $user->id)->exists();

        if ($alreadyFollowed || $alreadyRequested) {
            return back()->with('info', 'You have already sent a request or followed this user.');
        }

        if ($targetProfile->status === 'public') {
            DB::transaction(function () use ($authId, $user, $targetProfile) {
                FollowRelation::create([
                    'user_id' => $authId,
                    'follow_user_id' => $user->id
                ]);

                $targetProfile->increment('followers');

                auth()->user()->profile->increment('following');

                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'follow',
                    'title' => 'New Follower',
                    'message' => auth()->user()->name . ' started following you.',
                    'target_url' => route('profile.show', auth()->user()->username),
                    'is_read' => false
                ]);
            });
            return back()->with('success', 'You are now following ' . $user->name);
        } else {
            FollowRequest::create([
                'user_id' => $user->id,
                'requester_id' => $authId
            ]);
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'follow_request',
                'title' => 'Follow Request',
                'message' => auth()->user()->name . ' wants to follow you.',
                'target_url' => route('profile.follow-requests'),
                'is_read' => false
            ]);
            return back()->with('success', 'Follow request sent.');
        }
    }

    public function unfollow(User $user)
    {
        DB::transaction(function () use ($user) {
            FollowRelation::where('user_id', auth()->id())
                ->where('follow_user_id', $user->id)
                ->delete();

            $user->profile->decrement('followers');
            auth()->user()->profile->decrement('following');
        });
        return back();
    }

    public function cancelRequest(User $user)
    {
        FollowRequest::where('user_id', $user->id)
            ->where('requester_id', auth()->id())
            ->delete();
        return back();
    }

    public function followRequests()
    {
        $user = auth()->user();
        $requests = FollowRequest::where('user_id', $user->id)
                    ->with('requester.profile.careerPosition')
                    ->paginate(10);

        return view('profile.follow_requests', [
            'title' => 'Follow Requests',
            'users' => $requests,
            'user' => $user,
            'type' => 'requests'
        ]);
    }

    public function acceptRequest($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        
        if ($request->user_id !== auth()->id()) return back();

        DB::transaction(function () use ($request) {
            FollowRelation::create([
                'user_id' => $request->requester_id,
                'follow_user_id' => $request->user_id
            ]);

            User::find($request->user_id)->profile->increment('followers');
            User::find($request->requester_id)->profile->increment('following');

            UserNotification::create([
                'user_id' => $request->requester_id,
                'type' => 'follow_accepted',
                'title' => 'Request Accepted',
                'message' => auth()->user()->name . ' accepted your follow request.',
                'target_url' => route('profile.show', auth()->user()->username),
                'is_read' => false
            ]);

            $request->delete();
        });

        return back()->with('success', 'Request accepted!');
    }

    public function declineRequest($requestId)
    {
        $request = FollowRequest::findOrFail($requestId);
        if ($request->user_id !== auth()->id()) return back();
        
        $request->delete();
        return back()->with('success', 'Request declined.');
    }

    public function toggleFollow(User $user)
    {
        $auth = auth()->user();
        $targetProfile = $user->profile;

        // 1. Cek Relasi Follow (Followed)
        $relation = \App\Models\FollowRelation::where('user_id', $auth->id)
                    ->where('follow_user_id', $user->id)->first();

        if ($relation) {
            // UNFOLLOW ACTION
            \DB::transaction(function () use ($relation, $targetProfile, $auth) {
                $relation->delete();
                $targetProfile->decrement('followers');
                $auth->profile->decrement('following');
            });
            return response()->json(['status' => 'none', 'followers' => $targetProfile->followers]);
        }

        // 2. Cek Request (Requested)
        $request = \App\Models\FollowRequest::where('user_id', $user->id)
                    ->where('requester_id', $auth->id)->first();

        if ($request) {
            // CANCEL REQUEST ACTION
            $request->delete();
            return response()->json(['status' => 'none', 'followers' => $targetProfile->followers]);
        }

        // 3. FOLLOW ACTION
        if ($targetProfile->status === 'public') {
            \DB::transaction(function () use ($auth, $user, $targetProfile) {
                \App\Models\FollowRelation::create(['user_id' => $auth->id, 'follow_user_id' => $user->id]);
                $targetProfile->increment('followers');
                $auth->profile->increment('following');
            });
            return response()->json(['status' => 'followed', 'followers' => $targetProfile->followers]);
        } else {
            \App\Models\FollowRequest::create(['user_id' => $user->id, 'requester_id' => $auth->id]);
            return response()->json(['status' => 'requested', 'followers' => $targetProfile->followers]);
        }
    }
}