<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display list of users.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:admin,inventory_manager'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        AuditLog::record('user.created', $user, ['email' => $user->email, 'role' => $user->role]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user->makeHidden(['password', 'remember_token']),
                'message' => 'User created successfully.',
            ]);
        }

        return back()->with('status', 'User created successfully.');
    }

    /**
     * Update an existing user.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        AuditLog::record('user.updated', $user, ['email' => $user->email, 'role' => $user->role]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user->fresh()->makeHidden(['password', 'remember_token']),
                'message' => 'User updated successfully.',
            ]);
        }

        return back()->with('status', 'User updated successfully.');
    }

    /**
     * Remove a user (cannot delete self).
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            $message = 'You cannot delete your own account.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['user' => $message]);
        }

        $email = $user->email;
        $user->delete();
        AuditLog::record('user.deleted', null, ['email' => $email]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return back()->with('status', 'User deleted successfully.');
    }
}
