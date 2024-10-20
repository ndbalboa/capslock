<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
            $response = Http::timeout(150)
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
            'description' => 'nullable|string',
            'document_type' => 'required|string',
            'file_path' => 'required|string',
            'employee_names' => 'nullable|array', // Employee names as an array
        ]);

        try {
            // Save or update the document
            $document = Document::updateOrCreate(
                ['document_no' => $validatedData['document_no']],
                $validatedData
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
    public function getDocumentsByType($type)
    {
        // Fetch all documents with the provided document type
        $documents = Document::where('document_type', $type)->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No documents found for this type.'], 404);
        }

        return response()->json($documents);
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
        $document = Document::findOrFail($id);
        return Storage::download($document->file_path);
    }
    
}
