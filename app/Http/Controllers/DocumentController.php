<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function saveDocument(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

        // Store file
        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');

        // Get employee names from the request
        $employeeNames = $request->input('employee_names');

        // Query matching employees
        $employees = Employee::where(function ($query) use ($employeeNames) {
            foreach ($employeeNames as $name) {
                $names = explode(' ', trim($name));
                if (count($names) > 1) {
                    $query->orWhere(function ($q) use ($names) {
                        $q->where('firstName', 'like', '%' . $names[0] . '%')
                          ->where('lastName', 'like', '%' . $names[1] . '%');
                    });
                }
            }
        })->get();

        // Create document record
        $document = Document::create([
            'document_no' => $request->input('document_no'),
            'date_issued' => $request->input('date_issued'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'document_type' => $request->input('document_type'),
            'file_path' => $filePath,
        ]);

        // Attach employees to the document
        if ($employees->count() > 0) {
            $document->employees()->attach($employees);
        }

        return response()->json(['message' => 'Document and file saved successfully']);
    }
}

