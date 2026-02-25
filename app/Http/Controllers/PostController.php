<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $user = auth()->user();

        // Create the post
        $user->posts()->create([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        // ✅ No need to call Spatie complete() here
        // Progress bar now calculates completed steps in Blade

        return redirect('/dashboard')->with('success', 'Post created!');
    }
}