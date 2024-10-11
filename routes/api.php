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

// Employee Routes (Admin only)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('employees/deactivated', [EmployeeController::class, 'getDeactivatedEmployees']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('employees/{id}', [EmployeeController::class, 'show']);
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'delete']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::put('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate']);
    Route::post('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate']);
    Route::put('/employees/{id}/reactivate', [EmployeeController::class, 'reactivate']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore']);
    Route::delete('/employees/{id}/forceDelete', [EmployeeController::class, 'forceDeleteEmployee']);
    Route::post('/upload-scanned-document', [ScanController::class, 'upload']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::post('/documents/save', [DocumentController::class, 'save']);
    Route::post('/employees/register-unregistered', [EmployeeController::class, 'registerUnregisteredEmployee']);


});

// User Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employee-profile', [EmployeeProfileController::class, 'show']);
    Route::post('/employee-profile', [EmployeeProfileController::class, 'update']);
    Route::get('/user/profile', [UserController::class, 'fetchUserProfile']);
    Route::put('/user/profile', [UserController::class, 'updateUserProfile']);
    Route::post('/user/upload-image', [UserController::class, 'uploadImage']);
    Route::put('/user/current-username', [UserController::class, 'changeCredentials']);//////
    Route::put('/user/change-credentials', [UserController::class, 'changeCredentials']);
});

// Ensure there are no conflicts or redundancy
