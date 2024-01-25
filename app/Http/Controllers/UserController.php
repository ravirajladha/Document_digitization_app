<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;


use Illuminate\Validation\Rules;
use App\Models\{User, Permission};

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    public function showUsers()
    {
        $users = User::where('type', "user") // Add the count of document assignments
            ->orderBy('created_at', 'desc')
            ->get();

            $permissions = Permission::select('id', 'name', 'display_name', 'action')
            ->orderByRaw("FIELD(action, '1', '2', '3')") // Order by the sequence of actions
            ->get();
// dd($permissions);
        return view('pages.users.users', [
            'users' => $users,
            'permissions' => $permissions,
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
    $permissionIds = $request->input('permissions', []);
    // dd($permissionIds, $user->id);
    // $assign_permission = $this->assignPermissions($permissionIds, $user->id);
    // return redirect()->route('users.index')->with('success', 'User created successfully.');
    try {
        // Attempt to assign permissions
        $assign_permission = $this->assignPermissions($permissionIds, $user->id);
        // If successful, redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created and permissions assigned successfully.');
    } catch (\Throwable $th) {
        // Handle the error, redirect back with an error message
        return redirect()->route('users.index')->with('error', 'Failed to assign permissions.');
    }
    // Additional logic after user creation (e.g., redirect, flash message, etc.)

 
}


public function assignPermissions($permissions, $userId)
{
    $user = User::findOrFail($userId); // Find the user or fail
    
    // Start a transaction to ensure all operations are done atomically
    DB::beginTransaction();
    
    try {
        // Remove any existing permissions
        $user->permissions()->detach();

        // Loop through each permission and add it to the user_has_permissions table
        foreach ($permissions as $permissionId) {
            // Insert each permission individually
            $user->permissions()->attach($permissionId);
        }

        // Commit the transaction
        DB::commit();
        return true;
    } catch (\Throwable $th) {
        // Rollback the transaction in case of any error
        DB::rollBack();
        report($th);
        throw $th;
    }
}

public function edit($id)
{
    // Retrieve the user with the given id and load their permissions
    $user = User::with('permissions')->findOrFail($id);

    // Retrieve all permissions
    $permissions = Permission::select('id', 'name', 'display_name', 'action')
        ->orderByRaw("FIELD(action, '1', '2', '3')") // Order by the sequence of actions
        ->get();

    // Pass the user and permissions to the edit view
    return view('pages.users.edit-user', [
        'user' => $user,
        'permissions' => $permissions,
    ]);
}

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|integer',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            // Make the password optional and only validate if it's provided
            'password' => $request->filled('password') ? ['confirmed', Rules\Password::defaults()] : '',
        ]);
    
        // Update the user's information
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];
        $user->email = $validatedData['email'];
    
        // If a new password was provided, hash and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        // Update the user's permissions
        if ($request->has('permissions')) {
            $permissionIds = $request->input('permissions');
            $this->assignPermissions($permissionIds, $user->id);
        }
    
        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

}
