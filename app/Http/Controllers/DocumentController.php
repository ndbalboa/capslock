<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use App\Models\DocumentEmployeeName;
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
            'date_issued' => 'nullable|date',
            'from_date' => 'nullable|string',
            'to_date' => 'nullable|string',
            'subject' => 'nullable|string',
            'description' => 'nullable|string',
            'document_type' => 'required|string',
            'file_path' => 'required|string',
        ]);

        try {
            // Save or update the document
            $document = Document::updateOrCreate(
                ['document_no' => $validatedData['document_no']],
                $validatedData
            );

            // Handle employee_names separately for both registered and unregistered employees
            if ($request->has('employee_names')) {
                $employeeNames = $request->input('employee_names');
                if (!empty($employeeNames)) {
                    // Attempt to associate employees, and handle unregistered employees
                    $this->associateEmployees($document, $employeeNames);
                }
            }

            // Send feedback to Flask API
            $feedbackData = array_filter($validatedData, function ($value) {
                return !is_null($value) && $value !== '';
            });
            Http::post('http://localhost:5000/api/admin/feedback', $feedbackData);

            return response()->json([
                'message' => 'Document saved successfully.',
                'document' => $document,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save the document: ' . $e->getMessage()], 500);
        }
    }

    // Method to associate employees (both registered and unregistered)
    private function associateEmployees($document, $employeeNames)
    {
        foreach ($employeeNames as $employeeName) {
            $names = explode(' ', $employeeName);
            $firstName = $names[0] ?? null;
            $lastName = end($names);

            // Try to find or create the employee (registered employee)
            $employee = Employee::where('firstname', $firstName)
                ->where('lastname', $lastName)
                ->first();

            if ($employee) {
                // Associate the registered employee with the document
                try {
                    $document->employees()->syncWithoutDetaching($employee->id);
                } catch (\Exception $e) {
                    \Log::error('Failed to associate employee with document: ' . $e->getMessage());
                }
            } else {
                // If the employee is not found, store the name as an unregistered employee
                $document->documentEmployeeNames()->create([
                    'employee_name' => $employeeName,
                ]);
            }
        }
    }
}
