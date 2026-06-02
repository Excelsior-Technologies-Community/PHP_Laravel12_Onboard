<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['onboarding_step' => 1, 'completed' => false]
        );

        if (!$profile->completed) {
            return redirect()->route('onboarding.wizard');
        }

        return view('dashboard', compact('user', 'profile'));
    }

    public function wizard()
    {
        $user = Auth::user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        if ($profile->completed) {
            return redirect()->route('dashboard');
        }

        $completeness = $this->calculateCompleteness($profile);

        return view('profile', compact('user', 'profile', 'completeness'));
    }

    public function submitWizard(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->firstOrFail();
        $currentStep = $profile->onboarding_step;

        if ($currentStep == 1) {
            $request->validate([
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
            ]);
            $profile->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'onboarding_step' => 2
            ]);
        } elseif ($currentStep == 2) {
            $request->validate([
                'bio' => 'required|string',
                'location' => 'required|string',
                'skills' => 'required|string',
            ]);
            $profile->update([
                'bio' => $request->bio,
                'location' => $request->location,
                'skills' => $request->skills,
                'onboarding_step' => 3
            ]);
        } elseif ($currentStep == 3) {
            if ($request->hasFile('image')) {
                $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
                $path = $request->file('image')->store('profiles', 'public');
                $profile->image = $path;
            }
            $profile->completed = true;
            $profile->save();

            return redirect()->route('dashboard')->with('success', 'Onboarding completed successfully!');
        }

        return redirect()->route('onboarding.wizard');
    }

    public function index()
    {
        $user = auth()->user();
        $profile = $user->profile ?? Profile::firstOrCreate(['user_id' => $user->id]);
        $completeness = $this->calculateCompleteness($profile);

        return view('profile', compact('user', 'profile', 'completeness'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $user = Auth::user();
        $profile = $user->profile ?? Profile::firstOrCreate(['user_id' => $user->id]);
        $imagePath = $profile->image ?? null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $imagePath,
                'completed' => true,
            ]
        );

        return redirect('/dashboard')->with('success', 'Profile updated successfully!');
    }

    private function calculateCompleteness($profile)
    {
        $score = 0;
        if ($profile->phone) $score += 20;
        if ($profile->address) $score += 20;
        if ($profile->bio) $score += 20;
        if ($profile->skills) $score += 20;
        if ($profile->image) $score += 20;
        return $score;
    }
}