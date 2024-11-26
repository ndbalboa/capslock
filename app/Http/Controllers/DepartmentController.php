<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getDocumentsByDepartment(Request $request)
    {
        // Get the logged-in user's department
        $user = auth()->user();
        $department = $user->department; // Automatically use the user's department
        $documentType = $request->input('document_type'); // E.g., 'Travel Order'
    
        if (!$department) {
            return response()->json(['error' => 'User is not associated with a department.'], 403);
        }
    
        // Query employees matching the department
        $employeesInDepartment = Employee::where('department', $department)->get();
    
        // Extract names of employees in the department
        $employeeNames = $employeesInDepartment->map(function ($employee) {
            return $employee->lastName . ', ' . $employee->firstName;
        })->toArray();
    
        // Query documents with the specified document type and matching employee names
        $documents = Document::with('documentType')
            ->whereHas('documentType', function ($query) use ($documentType) {
                if ($documentType) {
                    $query->where('document_type', $documentType);
                }
            })
            ->where(function ($query) use ($employeeNames) {
                foreach ($employeeNames as $name) {
                    $query->orWhereJsonContains('employee_names', $name);
                }
            })
            ->get()
            ->map(function ($document) {
                return [
                    'document_no' => $document->document_no,
                    'employee_names' => $document->employee_names,
                    'document_type' => $document->documentType->document_type,
                ];
            });
    
        return response()->json($documents);
    }
    public function index()
    {
        $documentTypes = DocumentType::pluck('document_type'); // Fetch only document type names
        return response()->json($documentTypes);
    }
    
}
