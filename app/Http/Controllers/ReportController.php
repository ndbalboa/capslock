<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Employee;
use App\Models\GeneratedReport;
use PDF;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::where('document_type', 'travel order');

        if ($request->startDate && $request->endDate) {
            $query->whereBetween('date_issued', [
                Carbon::parse($request->startDate),
                Carbon::parse($request->endDate)
            ]);
        }

        if ($request->employee) {
            $employee = Employee::find($request->employee);
            if ($employee) {
                $query->whereJsonContains('employee_names', $employee->full_name);
            }
        }

        $travelOrders = $query->get();

        return response()->json(['travelOrders' => $travelOrders]);
    }

    public function getEmployees()
    {
        $employees = Employee::all(['id', 'firstName', 'lastName']);
        return response()->json([
            'employees' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                ];
            })
        ]);
    }
    public function generateReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $employee = $request->employee;
        $documentType = $request->documentType;

        // Query documents based on filters
        $documents = Document::where('document_type', $documentType)
                             ->whereBetween('date_issued', [$startDate, $endDate])
                             ->when($employee, function ($query) use ($employee) {
                                 return $query->whereJsonContains('employee_names', ['lastName' => $employee]);
                             })
                             ->get();

        // Generate the PDF report
        $pdf = PDF::loadView('reports.document_report', compact('documents'));
        $pdfPath = 'reports/' . $documentType . '_report_' . time() . '.pdf';
        Storage::put('public/' . $pdfPath, $pdf->output());

        // Store generated report information in `generated_reports` table
        $report = GeneratedReport::create([
            'report_name' => ucfirst($documentType) . ' Report ' . date('Y-m-d'),
            'file_path' => 'storage/' . $pdfPath,
            'report_type' => $documentType,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return response()->json([
            'pdfPath' => asset('storage/' . $pdfPath),
            'documents' => $documents,
            'report' => $report,
        ]);
    }
    public function generateDocumentReport(Request $request)
    {
        // Validate request parameters, including the document type
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'document_type' => 'required|string|in:Travel Order,Office Order,Special Order',
            'employee_name' => 'nullable|string',
        ]);
    
        // Base query for filtering by document type and date range
        $query = Document::where('document_type', $request->document_type)
                        ->whereBetween('date_issued', [$request->start_date, $request->end_date]);
    
        // Optionally filter by employee name if provided
        if ($request->filled('employee_name')) {
            $query->whereJsonContains('employee_names', $request->employee_name);
        }
    
        // Fetch the filtered documents
        $documents = $query->get();
    
        // Return the documents as a response
        return response()->json($documents);
    }
    
    public function listReports()
    {
        // Assuming reports are stored in a 'reports' directory under 'storage/app/public'
        $files = Storage::disk('public')->files('reports');

        $reports = array_map(function ($file) {
            return [
                'fileName' => basename($file),
                'filePath' => $file,
                'created_at' => Storage::disk('public')->lastModified($file)  // Get file's last modified timestamp
            ];
        }, $files);

        // Sort reports by creation date descending
        usort($reports, fn($a, $b) => $b['created_at'] - $a['created_at']);

        return response()->json(['reports' => $reports]);
    }

    // Delete a specific report
    public function deleteReport(Request $request)
    {
        $request->validate([
            'filePath' => 'required|string',
        ]);

        $filePath = $request->input('filePath');

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['message' => 'Report deleted successfully.']);
        }

        return response()->json(['error' => 'File not found.'], 404);
    }
    public function employeename()
    {
        // Retrieve employees with full name (adjusting for column names in camel case)
        $employees = Employee::select('id', 'firstName', 'lastName')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->firstName . ' ' . $employee->lastName,
                ];
            });

        return response()->json(['employees' => $employees]);
    }

}
