<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Activity;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('username', 'password'))) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user's employee is soft-deleted
        if ($user->employee && $user->employee->trashed()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => ['Your account is associated with a deactivated employee record.'],
            ]);
        }

        // Check if the user's status is active
        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => ['Your account is inactive.'],
            ]);
        }

        // Log the login activity
        Activity::create([
            'user_id' => $user->id,
            'description' => 'Logged in to the system',
        ]);

        // Create an authentication token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and user role
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
        ]);
    }
    public function logout(Request $request)
    {
        // Ensure you're using the correct guard
        Auth::guard('web')->logout(); // Change 'web' if you have a different guard

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
    public function getRecentActivities()
    {
        $today = Carbon::today(); // Use Carbon for date manipulation
        $activities = Activity::with('user')->orderBy('created_at', 'desc')->get();

        // Count today's logins
        $loginCountToday = $activities->filter(function ($activity) use ($today) {
            return strpos($activity->description, 'login') !== false && 
                Carbon::parse($activity->created_at)->isToday();
        })->count();

        return response()->json([
            'activities' => $activities,
            'login_count_today' => $loginCountToday,
        ]);
    }

}
