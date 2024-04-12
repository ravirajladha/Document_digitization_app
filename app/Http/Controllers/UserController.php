<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;


use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

use App\Models\DocumentStatusLog;
use App\Models\{User, Permission};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        // dd($editUser);
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

    public function showReviewedDocumentsUsers($id)
    {
        $user_detail = User::find($id);
      
        // Check if the user exists
        if (!$user_detail) {
            // Handle the case where the user doesn't exist
            return response()->json(['error' => 'User not found'], 404);
        }


        // Get the counts for each status type for all documents
        $today = Carbon::now()->toDateString();

        // Get all distinct dates when document status was changed for the user
        $changedDates = DocumentStatusLog::where('created_by', $id)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->pluck('date');

        // Initialize an array to store the data
        $data = [];

        // Loop through each date
        foreach ($changedDates as $date) {
            $formattedDate = date('d/m/Y', strtotime($date));
            // Get the count of each status type for the current date
            $counts = DocumentStatusLog::where('created_by', $id)
                ->whereDate('created_at', $date)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            // Initialize an array to store the counts for each status type
            $statusCounts = [
                'Pending' => $counts[0] ?? 0,
                'Approved' => $counts[1] ?? 0,
                'Hold' => $counts[2] ?? 0,
                'Reviewer Feedback' => $counts[3] ?? 0,
                'Total' => ($counts[0] ?? 0) + ($counts[1] ?? 0) + ($counts[2] ?? 0) + ($counts[3] ?? 0),
            ];

            // Add the counts to the data array
            $data[$formattedDate] = $statusCounts;
        }

        // Get the total counts for each status type
        $totalCounts = DocumentStatusLog::where('created_by', $id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Initialize an array to store the total counts
        $totalStatusCounts = [
            'Pending' => $totalCounts[0] ?? 0,
            'Approved' => $totalCounts[1] ?? 0,
            'Hold' => $totalCounts[2] ?? 0,
            'Reviewer Feedback' => $totalCounts[3] ?? 0,
            'Total' => ($totalCounts[0] ?? 0) + ($totalCounts[1] ?? 0) + ($totalCounts[2] ?? 0) + ($totalCounts[3] ?? 0),

        ];

        // Add the total counts to the data array
        $data['Total'] = $totalStatusCounts;

        // Get the counts for each status type for documents approved today
        $todayCounts = DocumentStatusLog::where('created_by', $id)
            ->whereDate('created_at', $today)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        //dd($data, $todayCounts);
        // Return the view with the counts
        return view('pages.users.reviewed-documents', [
            'data' => $data,
            'todayCounts' => $todayCounts,
            'user_detail' => $user_detail,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|integer|digits:10',

            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'], // Add regex rule for password strength
            'password_confirmation' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'type' => "user", // Set user type as 'user'
            'status' => $request->status, // Set the user status
        ]);

        $requestedPermissions = $request->input('permissions', []);
        $permissionNames = array_keys($requestedPermissions);
        // dd($permissionNames);
        // dd($permissionIds, $user->id);
        // $assign_permission = $this->assignPermissions($permissionIds, $user->id);
        // return redirect()->route('users.index')->with('success', 'User created successfully.');
        try {
            // Attempt to assign permissions

            $assign_permission = $this->assignPermissions($permissionNames, $user->id);
            session()->flash('toastr', ['type' => 'success', 'message' => 'User created successfully.']);

            // If successful, redirect with a success message
            return redirect()->route('users.index')->with('success', 'User created and permissions assigned successfully.');
        } catch (\Throwable $th) {
            // Handle the error, redirect back with an error message
            return redirect()->route('users.index')->with('error', 'Failed to assign permissions.');
        }
        // Additional logic after user creation (e.g., redirect, flash message, etc.)
    }


    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|integer|digits:10',

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',

                    Rule::unique(User::class)->ignore($user->id),
                ],
                'password' => [
                    'nullable',
                    'confirmed',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
                ],
                'status' => 'required|in:0,1',

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
            $permissionNames = array_keys($requestedPermissions);

            // dd($permissionNames, $user->id);
            $assign_permission = $this->assignPermissions($permissionNames, $user->id);

            // Update the user's information
            $user->name = $validatedData['name'];
            $user->phone = $validatedData['phone'];
            $user->status = $validatedData['status'];
            $user->email = strtolower($validatedData['email']); // Store the email in lowercase

            // If a new password was provided, hash and update it
            if ($request->filled('password') == $request->filled('password_confirmation')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            session()->flash('toastr', ['type' => 'success', 'message' => 'User updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            // Redirect to the edit route with the input except the password and with errors
            return redirect()->route('users.edit', ['user' => $id])
                ->withErrors($exception->validator)
                ->withInput($request->except('password'));
        }

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    public function assignPermissions($permissionNames, $userId)
    {
        // dd($permissionNames,$userId);
        $user = User::findOrFail($userId);

        DB::beginTransaction();
        try {
            // Remove any existing permissions
            $user->permissions()->detach();

            // Get the permissions by display_name
            $permissions = Permission::whereIn('display_name', $permissionNames)->get(['display_name']);
            // Check if the permissions were found
            if ($permissions->count() !== count($permissionNames)) {
                throw new \Exception("One or more permissions could not be found.");
            }

            // Attach the new permissions using their display names
            foreach ($permissions as $permission) {
                $user->permissions()->attach($permission->display_name);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th); // Make sure to log the exception
            throw $th;
        }
    }
}
