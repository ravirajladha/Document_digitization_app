<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function showUsers()
    {
        $users = User::where('type', "user") // Add the count of document assignments
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.users', [
            'users' => $users,
        ]);
    }


    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|integer',
        'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'password_confirmation' => 'required',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'type' => "user", // Set user type as 'user'
    ]);

    // Additional logic after user creation (e.g., redirect, flash message, etc.)

    return redirect()->route('showUsers')->with('success', 'User created successfully.');
}
}
