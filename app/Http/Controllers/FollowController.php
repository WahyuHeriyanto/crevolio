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
}