<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\Employee;
use App\Models\DocumentType;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    // Upload and validate the document
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,jpg,png,docx|max:20480',
        ]);
    
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');
    
        try {
            $response = Http::timeout(500)
                ->attach('file', file_get_contents($file->getPathname()), $fileName)
                ->post('http://localhost:5000/api/admin/upload');
    
            if ($response->failed()) {
                $errorMessage = $response->json('error') ?? 'Failed to communicate with Flask API';
                Storage::delete($filePath);
                return response()->json(['error' => $errorMessage], 500);
            }
    
            $data = $response->json();
            if (isset($data['error'])) {
                Storage::delete($filePath);
                return response()->json(['error' => $data['error']], 400);
            }
    
            $extractedFields = $data['extracted_fields'];
            $extractedFields['file_path'] = $filePath;
            $extractedFields['document_type'] = $data['document_type'];
    
            return response()->json([
                'document' => $extractedFields,
            ]);
        } catch (\Exception $e) {
            Storage::delete($filePath);
            return response()->json(['error' => 'Failed to process the document: ' . $e->getMessage()], 500);
        }
    }
    
    // Save the document and handle employee associations (both registered and unregistered)
    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'document_no' => 'nullable|string',
            'series_no' => 'nullable|string',
            'date_issued' => 'nullable|date',
            'from_date' => 'nullable|string',
            'to_date' => 'nullable|string',
            'subject' => 'nullable|string',
            'venue' => 'nullable|string',
            'destination' => 'nullable|string',
            'description' => 'nullable|string',
            'document_type' => 'required|string', // Document type as a string
            'file_path' => 'required|string',
            'employee_names' => 'nullable|array', // Employee names as an array
        ]);
    
        try {
            // Retrieve the document type based on the string passed
            $documentType = DocumentType::where('document_type', $validatedData['document_type'])->first();
    
            // If the document type doesn't exist, return an error
            if (!$documentType) {
                return response()->json(['error' => 'Invalid document type.'], 400);
            }
    
            // Merge the document_type_id into the validated data
            $validatedData['document_type_id'] = $documentType->id;
    
            // Save or update the document
            $document = Document::updateOrCreate(
                ['document_no' => $validatedData['document_no']],
                $validatedData // This now includes the document_type_id
            );
    
            // Save employee names directly in the document's employee_names field
            if ($request->has('employee_names')) {
                $document->employee_names = $validatedData['employee_names'];
                $document->save();
            }
    
            // Send feedback to Flask API
            Http::post('http://localhost:5000/api/admin/feedback', $validatedData);
    
            return response()->json([
                'message' => 'Document saved successfully.',
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save the document: ' . $e->getMessage()], 500);
        }
    }
    
    private function attachEmployeesToDocument(array $employeeNames, Document $document)
    {
        foreach ($employeeNames as $employeeName) {
            // Split the employee name into first and last name (assuming "FirstName LastName" format)
            $nameParts = explode(' ', $employeeName);
            $firstName = $nameParts[0] ?? null;
            $lastName = $nameParts[1] ?? null;

            if ($firstName && $lastName) {
                // Check if the employee exists in the database
                $employee = Employee::where('firstName', $firstName)
                    ->where('lastName', $lastName)
                    ->first();

                if ($employee) {
                    // Attach the employee to the document (many-to-many relationship)
                    $document->employees()->attach($employee->id);
                } else {
                    // Handle unregistered employees by storing the name in the document_employee_names table
                    $document->employeeNames()->create([
                        'full_name' => $employeeName
                    ]);
                }
            }
        }
    }


    // Fetch documents by employee ID
    public function getDocumentsByEmployee(Employee $employee)
    {
        // Concatenate the employee's first and last name
        $employeeFullName = $employee->firstName . ' ' . $employee->lastName;

        // Fetch all documents that contain the employee's full name in the employee_names JSON field
        $documents = Document::whereJsonContains('employee_names', $employeeFullName)->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No documents found for this employee.'], 404);
        }

        return response()->json($documents);
    }

    // New function to fetch documents based on employee ID
    public function getEmployeeDocuments($id)
    {
        // Fetch the employee by ID
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }

        // Concatenate the employee's first and last name
        $employeeFullName = $employee->firstName . ' ' . $employee->lastName;

        // Fetch all documents that contain the employee's full name in the employee_names JSON field
        $documents = Document::whereJsonContains('employee_names', $employeeFullName)->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No documents found for this employee.'], 404);
        }

        return response()->json([
            'employee' => [
                'firstName' => $employee->firstName,
                'lastName' => $employee->lastName,
            ],
            'documents' => $documents
        ]);
    }
    public function getDocumentById($id)
    {
        try {
            // Find the document by its ID
            $document = Document::findOrFail($id);

            // Return the document as a JSON response
            return response()->json($document, 200);
        } catch (\Exception $e) {
            // If the document is not found or another error occurs
            return response()->json([
                'error' => 'Document not found or an error occurred.'
            ], 404);
        }
    }
    public function download($id)
    {
        try {
            $document = Document::findOrFail($id);

            // Ensure the file exists before downloading
            if (!Storage::exists($document->file_path)) {
                return response()->json(['error' => 'File not found.'], 404);
            }

            // Return the file for download
            return Storage::download($document->file_path);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while downloading the file.'], 500);
        }
    }
    public function getUserDocuments(Request $request)
    {
        // Get the authenticated user's ID
        $userId = $request->user()->id;

        // Get the employee associated with the authenticated user
        $employee = User::find($userId)->employee;

        // Check if the employee exists
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Fetch documents associated with the employee
        $documents = Document::whereJsonContains('employee_names', [
            'firstName' => $employee->firstName,
            'lastName' => $employee->lastName,
        ])->get();

        // Return the documents as a JSON response
        return response()->json($documents);
    }
    public function search(Request $request)
    {
        // Get the search query from the request
        $searchQuery = $request->input('searchQuery');
        
        // Perform a search on documents, including the document type relationship
        $documents = Document::with('documentType')
            ->where('document_no', 'LIKE', "%$searchQuery%")
            ->orWhere('series_no', 'LIKE', "%$searchQuery%")
            ->orWhere('subject', 'LIKE', "%$searchQuery%")
            ->orWhere('description', 'LIKE', "%$searchQuery%")
            ->orWhereJsonContains('employee_names', $searchQuery)
            ->get();
    
        // Append the actual document type to each document
        $documents->each(function ($document) {
            $document->actual_document_type = $document->documentType->document_type ?? 'Unknown';
        });
    
        return response()->json($documents);
    }
    

    // Advanced search function
    public function advancedSearch(Request $request)
    {
        // Get advanced search parameters from the request
        $keyword = $request->input('keyword');
        $dateFrom = $request->input('dateFrom');
        $documentNumber = $request->input('documentNumber');
        $employee = $request->input('employee');
        $subject = $request->input('subject');
        $from = $request->input('from');
        $to = $request->input('to');

        // Build query based on search filters
        $query = Document::query();

        if ($keyword) {
            $query->where('document_type', 'LIKE', "%$keyword%");
        }

        if ($dateFrom) {
            $query->whereDate('date_issued', '>=', $dateFrom);
        }

        if ($documentNumber) {
            $query->where('document_no', 'LIKE', "%$documentNumber%");
        }

        if ($employee) {
            $query->whereJsonContains('employee_names', $employee);
        }

        if ($subject) {
            $query->where('subject', 'LIKE', "%$subject%");
        }

        if ($from) {
            $query->where('from_date', 'LIKE', "%$from%");
        }

        if ($to) {
            $query->where('to_date', 'LIKE', "%$to%");
        }

        // Get the filtered documents
        $documents = $query->get();

        return response()->json($documents);
    }
    // admin interface for getting documents by type
    public function getDocumentsByType($documentTypeId)
    {
        $documents = Document::where('document_type_id', $documentTypeId)->get();
        return response()->json($documents);
    }
    public function getDocumentDetails($documentId)
    {
        $document = Document::with('documentType')->findOrFail($documentId);
    
        // Add the actual document type to the response
        $document->actual_document_type = $document->documentType->document_type ?? 'Unknown';
    
        return response()->json($document);
    }
    
    

    public function getDocumentsForUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Fetch documents based on the user
        $documents = Document::whereJsonContains('employee_names', $user->firstName . ' ' . $user->lastName)->get();

        return response()->json($documents);
    }

   
    public function getAllDocuments()
    {
        $documents = Document::all();
        return response()->json($documents);
    }
 // For the user to search their own documents
 public function userSearch(Request $request)
 {
     $user = Auth::user(); // Get the authenticated user
     $searchQuery = $request->input('searchQuery');
 
     // Search documents associated with the user, including the document type
     $documents = Document::with('documentType')
         ->whereJsonContains('employee_names', $user->firstName . ' ' . $user->lastName)
         ->where(function ($query) use ($searchQuery) {
             if ($searchQuery) {
                 $query->where('document_no', 'like', '%' . $searchQuery . '%')
                       ->orWhere('subject', 'like', '%' . $searchQuery . '%');
             }
         })
         ->get();
 
     // Append the actual document type to each document
     $documents->each(function ($document) {
         $document->actual_document_type = $document->documentType->document_type ?? 'Unknown';
     });
 
     return response()->json($documents);
 }
 

 // For advanced search by the user
 public function userAdvancedSearch(Request $request)
 {
     $user = Auth::user(); // Get the authenticated user
     $advancedSearchQuery = $request->all();

     // Build the query for advanced search
     $query = Document::whereJsonContains('employee_names', $user->firstName . ' ' . $user->lastName);

     if (!empty($advancedSearchQuery['keyword'])) {
         $query->where(function($q) use ($advancedSearchQuery) {
             $q->where('subject', 'like', '%' . $advancedSearchQuery['keyword'] . '%')
               ->orWhere('description', 'like', '%' . $advancedSearchQuery['keyword'] . '%');
         });
     }
     if (!empty($advancedSearchQuery['documentType'])) {
        $query->where('document_type', '>=', $advancedSearchQuery['documentType']);
    }

     if (!empty($advancedSearchQuery['dateFrom'])) {
         $query->where('date_issued', '>=', $advancedSearchQuery['dateFrom']);
     }

     if (!empty($advancedSearchQuery['documentNumber'])) {
         $query->where('document_no', 'like', '%' . $advancedSearchQuery['documentNumber'] . '%');
     }

     if (!empty($advancedSearchQuery['employee'])) {
         $query->whereJsonContains('employee_names', $advancedSearchQuery['employee']);
     }

     if (!empty($advancedSearchQuery['subject'])) {
         $query->where('subject', 'like', '%' . $advancedSearchQuery['subject'] . '%');
     }

     if (!empty($advancedSearchQuery['from'])) {
         $query->where('from_date', '>=', $advancedSearchQuery['from']);
     }

     if (!empty($advancedSearchQuery['to'])) {
         $query->where('to_date', '<=', $advancedSearchQuery['to']);
     }

     $documents = $query->get();

     return response()->json($documents);
 }

    // for user interface fetching documents by type
    public function getUserDocumentsByType($type = null)
    {
        try {
            $user = Auth::user();  // Get the authenticated user

            // Fetch documents by type if provided, otherwise fetch all types
            $documentsQuery = $type 
                ? Document::forUser($user, $type) 
                : Document::forUser($user);

            // Eager load the documentType to get the actual document type
            $documents = $documentsQuery->with('documentType')->get();

            // Check if documents are found
            if ($documents->isEmpty()) {
                return response()->json(['message' => 'No documents found for this user'], 404);
            }

            // Add actual document type to each document
            $documents->each(function ($document) {
                $document->actual_document_type = $document->documentType->document_type ?? 'Unknown';
            });

            return response()->json($documents, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching user documents: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching documents'], 500);
        }
    }
    

    //for document counting on admin dashboard
    public function getDocumentCounts()
    {
        // Get the total number of documents
        $totalDocuments = Document::count();
    
        // Get the count of documents grouped by document type
        $countsByType = Document::select('document_types.document_type', DB::raw('count(*) as count'))
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id') // Join with document_types table
            ->groupBy('document_types.document_type')  // Group by the document_type from the document_types table
            ->get();
    
        return response()->json([
            'total' => $totalDocuments,   // Total count of all documents
            'counts' => $countsByType,    // Count grouped by document type
        ]);
    }
    
    public function getUserDocumentCounts(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Fetch documents where employee_names contains the user's last name and first name
        $documents = Document::whereJsonContains('employee_names', $user->firstName . ' ' . $user->lastName)
                            ->get();

        // Count documents by type
        $documentCounts = $documents->groupBy('document_type')->map(function ($group) {
            return $group->count();
        });

        return response()->json([
            'total' => $documents->count(),
            'counts' => $documentCounts,
        ]);
    }

    public function updateDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validatedData = $request->validate([
            'document_no' => 'string|nullable',
            'series_no' => 'string|nullable',
            'from_date' => 'date|nullable',
            'to_date' => 'date|nullable',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'date_issued' => 'date|nullable',
            'employee_names' => 'array|nullable',
        ]);

        $document->update($validatedData);

        return response()->json($document);
    }
    public function destroyDocument($id)
    {
        try {
            $document = Document::findOrFail($id);
            $document->delete();
            
            return response()->json(['message' => 'Document deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Document not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete document.'], 500);
        }
    }
//for department
public function getDocumentsByLoggedInDepartment()
{
    try {
        // Get the logged-in user
        $user = Auth::user();

        // Ensure the user has a department field
        $department = $user->department;

        if (!$department) {
            return response()->json(['error' => 'No department found for this user'], 400);
        }

        // Fetch employees in the department
        $employees = Employee::where('department', $department)->get();

        if ($employees->isEmpty()) {
            return response()->json(['error' => 'No employees found in this department'], 400);
        }

        // Fetch all documents associated with these employees based on firstName and lastName
        $documents = Document::where(function ($query) use ($employees) {
            foreach ($employees as $employee) {
                $query->orWhereJsonContains('employee_names', [
                    'firstName' => $employee->firstName,
                    'lastName' => $employee->lastName
                ]);
            }
        })->get();

        return response()->json($documents);
    } catch (\Exception $e) {
        // Log the error and return a 500 response
        \Log::error('Error fetching documents for department: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}


    
}
