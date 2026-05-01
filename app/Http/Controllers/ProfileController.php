<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $profile = $user->profile; // get existing profile

        return view('profile', compact('user', 'profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        // Get existing profile
        $profile = $user->profile;

        // Keep old image if not uploading new
        $imagePath = $profile->image ?? null;

        // Upload new image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        }

        // Save or update profile
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
}