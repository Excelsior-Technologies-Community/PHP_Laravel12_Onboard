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
        return view('profile', compact('user')); // make sure you have resources/views/profile/index.blade.php
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Save or update profile
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'address' => $request->address,
                'completed' => true, // ✅ mark as complete
            ]
        );

        return redirect('/dashboard')->with('success', 'Profile completed!');
    }
}