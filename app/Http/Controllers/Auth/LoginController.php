<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login page for the inventory & sales system.
     * If already logged in as admin or inventory_manager, redirect to /admin.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && in_array(Auth::user()->role ?? '', ['admin', 'inventory_manager'], true)) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login via Axios.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 422);
        }

        $request->session()->regenerate();

        AuditLog::record('auth.login', null, ['email' => $request->input('email')]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'redirect_to' => route('admin.dashboard'),
        ]);
    }

    /**
     * Log the user out of the system.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'redirect_to' => url('/'),
        ]);
    }
}

