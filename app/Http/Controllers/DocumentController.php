<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
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

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'document_no' => 'nullable|string',
            'date_issued' => 'nullable|date',
            'from' => 'nullable|string',
            'to' => 'nullable|string',
            'subject' => 'nullable|string',
            'description' => 'nullable|string',
            'document_type' => 'required|string',
            'file_path' => 'required|string',
            'employee_names' => 'nullable|array',
        ]);

        try {
            $document = Document::updateOrCreate(
                ['document_no' => $validatedData['document_no']],
                $validatedData
            );

            if (!empty($validatedData['employee_names'])) {
                $this->associateEmployees($document, $validatedData['employee_names']);
            }

            return response()->json([
                'message' => 'Document saved successfully.',
                'document' => $document,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save the document: ' . $e->getMessage()], 500);
        }
    }

    private function associateEmployees($document, $employeeNames)
    {
        foreach ($employeeNames as $employeeName) {
            $names = explode(' ', $employeeName);
            $firstName = $names[0] ?? null;
            $lastName = end($names);

            $employee = Employee::firstOrCreate([
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);

            $document->employees()->syncWithoutDetaching($employee->id);
        }
    }
}
