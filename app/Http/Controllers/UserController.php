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

    public function showUsers($userId = null)
    {
        $editUser = null;
        if ($userId) {
            $editUser = User::with('permissions')->findOrFail($userId);
        }
    
        $users = User::where('type', "user")
            ->orderBy('created_at', 'desc')
            ->get();
    
        $permissions = Permission::select('id', 'name', 'display_name', 'action')
            ->orderByRaw("FIELD(action, '1', '2', '3')") // Order by the sequence of actions
            ->get();
    
        return view('pages.users.users', [
            'users' => $users,
            'permissions' => $permissions,
            'editUser' => $editUser, // Pass the user to be edited, if any
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

    $requestedPermissions = $request->input('permissions', []);

    // Convert the permission IDs to a simple array
    $permissionIds = array_keys($requestedPermissions);



   
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
    $user = User::findOrFail($userId);
    DB::beginTransaction();
    
    try {
        // Remove any existing permissions
        $user->permissions()->detach();

        // Attach the new permissions
        $user->permissions()->attach($permissions);

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
        try {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            // Validate password if it's filled, and ensure it matches the confirmation and meets length requirements
            // 'password' => [
            //     'sometimes',
                
            //     'confirmed',
            //     'min:8',
            //     'max:20'
            // ],
            
        ]);
    

        $requestedPermissions = $request->input('permissions', []);

        // Convert the permission IDs to a simple array
        $permissionIds = array_keys($requestedPermissions);


        // Update the user's information
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];
        $user->email = strtolower($validatedData['email']); // Store the email in lowercase
    
        // If a new password was provided, hash and update it
        if ($request->filled('password') == $request->filled('password_confirmation')) {
            $user->password = Hash::make($request->password);
        }
        $assign_permission = $this->assignPermissions($permissionIds, $user->id);
    
        $user->save();
    
    } catch (\Illuminate\Validation\ValidationException $exception) {
        // Redirect to the edit route with the input except the password and with errors
        return redirect()->route('users.edit', ['user' => $id])
                         ->withErrors($exception->validator)
                         ->withInput($request->except('password'));
    }

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    
}
