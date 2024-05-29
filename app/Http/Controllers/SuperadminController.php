<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperadminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $name = $user->name;

        ActivityLogService::log('View', 'Viewed the list of users.');

        $users = User::all();
        return view('superadmin.index', compact('users', 'role', 'name'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        ActivityLogService::log('View', "Viewed user profile: {$user->name} (ID: {$user->id})");

        return view('superadmin.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        ActivityLogService::log('Edit', "Accessed edit page for user: {$user->name} (ID: {$user->id})");

        return view('superadmin.edit', compact('user'));
    }

    public function create()
    {
        ActivityLogService::log('View', 'Accessed create user page');

        return view('superadmin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|ends_with:cdd.edu.ph',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,viewer,encoder,superadmin',
            'superadmin_password' => 'required|string',
        ], [
            'email.ends_with' => 'The email must end with cdd.edu.ph domain.',
        ]);

        if (!Hash::check($request->superadmin_password, auth()->user()->password)) {
            ActivityLogService::log('Error', 'Failed to create user due to invalid superadmin password');

            return back()->withErrors(['superadmin_password' => 'Invalid superadmin password']);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        ActivityLogService::log('Create', 'Created a user: ' . "Name: " . $request->name . ' (Email: ' . $request->email . ') ' . ', ' . ' (Role: ' . $request->role . ')');

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . '|ends_with:cdd.edu.ph',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,viewer,encoder,superadmin',
        ], [
            'email.ends_with' => 'The email must end with cdd.edu.ph domain.',
        ]);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->save();

        $newData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        $changes = [];
        foreach ($oldData as $key => $value) {
            if ($oldData[$key] != $newData[$key]) {
                $changes[] = ucfirst($key) . " changed from '$value' to '" . $newData[$key] . "'";
            }
        }

        if ($passwordChanged) {
            $changes[] = "Password changed";
        }

        if (!empty($changes)) {
            $changeText = implode(", ", $changes);
            ActivityLogService::log('Update', "Updated user profile: {$user->name} (ID: {$user->id}), Changes: $changeText");
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function archives()
    {
        $archivedUsers = User::onlyTrashed()->get();

        ActivityLogService::log('View', 'Accessed archived users.');

        return view('superadmin.archives', compact('archivedUsers'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        ActivityLogService::log('Restore', 'Restored a user: ' . $user->name . ' (Email: ' . $user->email . ') ' . ', ' . ' (Role: ' . $user->role . ')');

        return redirect()->route('superadmin.archives')->with('success', 'User restored successfully.');
    }

    public function activityLogs()
    {
        $activityLogs = ActivityLog::with('user')->latest()->get();

        ActivityLogService::log('View', 'Viewed Activity Logs');

        return view('superadmin.activitylogs', compact('activityLogs'));
    }

    public function confirmDelete(Request $request)
    {
        $selectedUserIds = $request->input('selected_users', []);

        if (empty($selectedUserIds)) {
            ActivityLogService::log('Error', 'No users selected for deletion');

            return redirect()->route('superadmin.index')->with('error', 'No users selected for deletion.');
        }

        ActivityLogService::log('View', 'Accessed confirm delete page for selected users: ' . implode(', ', $selectedUserIds));

        return view('superadmin.confirm-delete', [
            'userIds' => $selectedUserIds
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); 

        ActivityLogService::log('Delete', 'Soft deleted a user: ' . $user->name . ' (Email: ' . $user->email . ') ' . ', ' . ' (Role: ' . $user->role . ')');

        return redirect()->route('superadmin.index')->with('success', 'User deleted successfully.');
    }
    
    public function destroyMultiple(Request $request)
    {
        $userIds = $request->input('usersToDelete', []);
        $superadminPassword = $request->input('superadmin_password');
 
        $validatedData = $request->validate([
            'superadmin_password' => 'required|string',
        ]);

        if (!Hash::check($superadminPassword, auth()->user()->password)) {
            ActivityLogService::log('Error', 'Failed to delete multiple users due to incorrect superadmin password');

            return redirect()->back()->with('error', 'Incorrect superadmin password.');
        }
    
        $deletedUsers = User::whereIn('id', $userIds)->delete();
    
        if ($deletedUsers > 0) {
            ActivityLogService::log('Delete users', 'Deleted selected users: ' . implode(', ', $userIds));
    
            return redirect()->route('superadmin.index')->with('success', 'Selected users deleted successfully.');
        } else {
            ActivityLogService::log('Error', 'Failed to delete selected users: ' . implode(', ', $userIds));

            return redirect()->route('superadmin.index')->with('error', 'Failed to delete selected users.');
        }
    }
}
