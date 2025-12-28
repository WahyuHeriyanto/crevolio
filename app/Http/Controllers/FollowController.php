<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FollowRelation;
use App\Models\FollowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $authId = auth()->id();
        $targetProfile = $user->profile;

        if ($targetProfile->status === 'public') {
            DB::transaction(function () use ($authId, $user, $targetProfile) {
                FollowRelation::create([
                    'user_id' => $authId,
                    'follow_user_id' => $user->id
                ]);

                $targetProfile->increment('followers');

                auth()->user()->profile->increment('following');
            });
            return back()->with('success', 'You are now following ' . $user->name);
        } else {
            FollowRequest::create([
                'user_id' => $user->id,
                'requester_id' => $authId
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