<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile;
use App\Models\UserExpertise;
use App\Models\UserTool;

class OnboardingController extends Controller
{
    public function index()
    {
        return view('onboarding.index', [
            'careerPositions' => \App\Models\CareerPosition::all(),
            'expertises' => \App\Models\Expertise::all(),
            'tools' => \App\Models\Tool::all(),
        ]);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $expertises = json_decode($request->expertises, true) ?? [];
        $tools = json_decode($request->tools, true) ?? [];

        $backgroundPath = null;
        $photoPath = null;

        if ($request->hasFile('background_image')) {
            $backgroundPath = $request->file('background_image')
                ->store('profile/backgrounds', 'public');
        }

        if ($request->hasFile('photo_profile')) {
            $photoPath = $request->file('photo_profile')
                ->store('profile/photos', 'public');
        }

        $profile = UserProfile::create([
            'user_id' => $userId,
            'gender' => $request->gender,
            'birth' => $request->birth,
            'career_position_id' => $request->career_position_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'photo_profile' => $photoPath,
            'background_image' => $backgroundPath,
            'status' => 'public',
            'followers' => 0,
            'following' => 0,
        ]);

        foreach ($expertises as $expertiseId) {
            UserExpertise::create([
                'user_profile_id' => $profile->id,
                'expertise_id' => $expertiseId,
                'custom_expertise' => $request->custom_expertise,
            ]);
        }

        foreach ($tools as $toolId) {
            UserTool::create([
                'user_profile_id' => $profile->id,
                'tool_id' => $toolId,
                'custom_tool' => $request->custom_tool,
            ]);
        }

        return redirect()->route('dashboard');
    }
}
