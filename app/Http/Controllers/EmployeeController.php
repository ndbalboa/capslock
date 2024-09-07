<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees with user relationship.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $employees = Employee::with('user')->paginate(10); // Paginate 10 employees per page
        return response()->json($employees);
    }

    /**
     * Store a newly created employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Store Request Data: ', $request->all());

        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $this->getValidatedData($request);

        // Handle profile image upload
        if ($request->hasFile('profileImage')) {
            $data['profileImage'] = $this->storeProfileImage($request);
        }

        $employee = Employee::create($data);

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee], 201);
    }

    /**
     * Display the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $employee = Employee::with('user')->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json(['employee' => $employee]);
    }

    /**
     * Update the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules($employee->id));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $this->getValidatedData($request);

        // Handle profile image upload
        if ($request->hasFile('profileImage')) {
            // Delete old image if exists
            if ($employee->profileImage && Storage::exists('public/' . $employee->profileImage)) {
                Storage::delete('public/' . $employee->profileImage);
            }

            $data['profileImage'] = $this->storeProfileImage($request);
        }

        $employee->update($data);

        return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee]);
    }

    /**
     * Remove the specified employee from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Delete profile image if exists
        if ($employee->profileImage && Storage::exists('public/' . $employee->profileImage)) {
            Storage::delete('public/' . $employee->profileImage);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }

    /**
     * Get validation rules for employee creation and updating.
     *
     * @param  int|null $employeeId
     * @return array
     */
    protected function getValidationRules($employeeId = null)
    {
        return [
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'sex' => 'required|string|max:10',
            'civilStatus' => 'required|string|max:20',
            'dateOfBirth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'emailAddress' => 'required|email|max:255|unique:employees,emailAddress,' . ($employeeId ?? 'NULL'),
            'phoneNumber' => 'required|string|max:15|unique:employees,phoneNumber,' . ($employeeId ?? 'NULL'),
            'gsisId' => 'nullable|string|max:255|unique:employees,gsisId,' . ($employeeId ?? 'NULL'),
            'pagibigId' => 'nullable|string|max:255|unique:employees,pagibigId,' . ($employeeId ?? 'NULL'),
            'philhealthId' => 'nullable|string|max:255|unique:employees,philhealthId,' . ($employeeId ?? 'NULL'),
            'sssNo' => 'nullable|string|max:255|unique:employees,sssNo,' . ($employeeId ?? 'NULL'),
            'agencyEmploymentNo' => 'nullable|string|max:255|unique:employees,agencyEmploymentNo,' . ($employeeId ?? 'NULL'),
            'taxId' => 'nullable|string|max:255|unique:employees,taxId,' . ($employeeId ?? 'NULL'),
            'academicRank' => 'required|string|max:50',
            'universityPosition' => 'required|string|max:50',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_city' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zipcode' => 'nullable|string|max:20',
            'residential_street' => 'nullable|string|max:255',
            'residential_barangay' => 'nullable|string|max:255',
            'residential_city' => 'nullable|string|max:255',
            'residential_province' => 'nullable|string|max:255',
            'residential_country' => 'nullable|string|max:255',
            'residential_zipcode' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get validated data from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getValidatedData(Request $request)
    {
        return $request->only([
            'lastName',
            'firstName',
            'middleName',
            'sex',
            'civilStatus',
            'dateOfBirth',
            'religion',
            'emailAddress',
            'phoneNumber',
            'gsisId',
            'pagibigId',
            'philhealthId',
            'sssNo',
            'agencyEmploymentNo',
            'taxId',
            'academicRank',
            'universityPosition',
            'permanent_street',
            'permanent_barangay',
            'permanent_city',
            'permanent_province',
            'permanent_country',
            'permanent_zipcode',
            'residential_street',
            'residential_barangay',
            'residential_city',
            'residential_province',
            'residential_country',
            'residential_zipcode',
        ]);
    }

    /**
     * Handle profile image storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function storeProfileImage(Request $request)
    {
        return $request->file('profileImage')->store('profile_images', 'public');
    }

    public function fetchUserProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function updateUserProfile(Request $request)
{
    try {
        // Validate incoming data
        $validatedData = $request->validate([
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
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

        $user = Auth::user();

        // Update user profile
        $user->update($validatedData);

        return response()->json($user);

    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error updating profile: ' . $e->getMessage());
        
        return response()->json(['error' => 'Unable to update profile'], 500);
    }
}

    public function uploadImage(Request $request)
    {
        $request->validate([
            'profileImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profileImage')) {
            $file = $request->file('profileImage');
            $filePath = $file->store('public/profile_images');
            $fileUrl = Storage::url($filePath);

            $user = Auth::user();
            $user->profileImage = $fileUrl;
            $user->save();

            return response()->json(['url' => $fileUrl]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
