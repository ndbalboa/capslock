<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile along with the associated employee information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = Auth::user()->load('employee'); // Assuming a one-to-one relationship with Employee

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load user profile: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a new user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
        ]);

        $user = User::create([
            'employee_id' => $request->employee_id,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Change the authenticated user's username and password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeCredentials(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = Auth::user();
            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json(['message' => 'Credentials updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update credentials: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'lastName' => 'nullable|string|max:255',
        'firstName' => 'nullable|string|max:255',
        'middleName' => 'nullable|string|max:255',
        'sex' => 'nullable|string|max:255',
        'civilStatus' => 'nullable|string|max:255',
        'dateOfBirth' => 'nullable|date',
        'religion' => 'nullable|string|max:255',
        'emailAddress' => 'nullable|email|max:255',
        'phoneNumber' => 'nullable|string|max:20',
        'gsisId' => 'nullable|string|max:255',
        'pagibigId' => 'nullable|string|max:255',
        'philhealthId' => 'nullable|string|max:255',
        'sssNo' => 'nullable|string|max:255',
        'agencyEmploymentNo' => 'nullable|string|max:255',
        'taxId' => 'nullable|string|max:255',
        'academicRank' => 'nullable|string|max:255',
        'universityPosition' => 'nullable|string|max:255',
        'permanent_street' => 'nullable|string|max:255',
        'permanent_barangay' => 'nullable|string|max:255',
        'permanent_city' => 'nullable|string|max:255',
        'permanent_province' => 'nullable|string|max:255',
        'permanent_country' => 'nullable|string|max:255',
        'permanent_zipcode' => 'nullable|string|max:10',
        'residential_street' => 'nullable|string|max:255',
        'residential_barangay' => 'nullable|string|max:255',
        'residential_city' => 'nullable|string|max:255',
        'residential_province' => 'nullable|string|max:255',
        'residential_country' => 'nullable|string|max:255',
        'residential_zipcode' => 'nullable|string|max:10',
    ]);

    try {
        $user = Auth::user();
        $employee = $user->employee;

        if ($employee) {
            // Update only the fields that are present in the validated data
            $employee->update(array_filter($validatedData));
            $employee->save();
        } else {
            // Create new employee record if it doesn't exist
            $user->employee()->create($validatedData);
        }

        return response()->json($user->load('employee'));
    } catch (\Exception $e) {
        Log::error('Error updating profile: ' . $e->getMessage());

        return response()->json(['error' => 'Unable to update profile'], 500);
    }
}

}