<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\DocumentController;


// Authentication Routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/recent-activities', [LoginController::class, 'getRecentActivities']);
Route::get('/documents/counts', [DocumentController::class, 'getDocumentCounts']);
Route::get('/user/documents/counts', [DocumentController::class, 'getUserDocumentCounts']);

// Employee Routes (Admin only)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::delete('/employees/{employeeId}/deactivate', [EmployeeController::class, 'deactivateEmployee']);
    Route::get('/employees/no-user-or-deleted', [EmployeeController::class, 'getDeactivatedEmployees']);
    Route::post('users/{employeeId}/activate', [UserController::class, 'activateUser']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('employees/{id}', [EmployeeController::class, 'show']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy'); 
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore']);
    Route::delete('/employees/{id}/forceDelete', [EmployeeController::class, 'forceDeleteEmployee']);
    Route::post('/upload-scanned-document', [ScanController::class, 'upload']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::post('/documents/save', [DocumentController::class, 'save']);
    Route::get('/employees/{employee}/documents', [DocumentController::class, 'getDocumentsByEmployee']); 
    Route::get('employees/{id}/documents', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/documents/employee/{id}', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/documents/type/{type}', [DocumentController::class, 'getDocumentsByType']);
    Route::get('/documents/{id}', [DocumentController::class, 'getDocumentById']);
    Route::get('/employees/{id}/documents', [EmployeeController::class, 'getDocuments']);
    Route::get('/employees/{id}/documents', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/employees/{id}', [EmployeeController::class, 'view']);
    Route::get('/employees/{id}/documents', [EmployeeController::class, 'documents']);
    Route::get('/documents/download/{id}', [DocumentController::class, 'download']);
    Route::post('/search', [DocumentController::class, 'search']);
    Route::post('/advanced-search', [DocumentController::class, 'advancedSearch']);
    Route::put('/documents/{id}', [DocumentController::class, 'updateDocument']);
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroyDocument']);
    
    

});

// User Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employee-profile', [EmployeeProfileController::class, 'show']);
    Route::post('/employee-profile', [EmployeeProfileController::class, 'update']);
    Route::get('/user/documents', [DocumentController::class, 'getUserDocuments']);
    Route::get('/user/profile', [UserController::class, 'fetchUserProfile']);
    Route::put('/user/profile', [UserController::class, 'updateUserProfile']);
    Route::post('/user/upload-image', [UserController::class, 'uploadImage']);
    Route::put('/user/current-username', [UserController::class, 'changeCredentials']);//////
    Route::put('/user/change-credentials', [UserController::class, 'changeCredentials']);
    Route::get('/user/{userId}/documents', [DocumentController::class, 'getDocumentsForUser']);
    Route::get('/documents', [DocumentController::class, 'getAllDocuments']);
    Route::post('/user/search', [DocumentController::class, 'userSearch']);
    Route::post('/user/advanced-search', [DocumentController::class, 'userAdvancedSearch']); 
    Route::get('/user/documents/{type?}', [DocumentController::class, 'getUserDocumentsByType']);
});

