<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;

// Authentication Routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/recent-activities', [LoginController::class, 'getRecentActivities']);

// Employee Routes (Admin only)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/admin/employees/deactivated', [EmployeeController::class, 'getDeactivatedEmployees']);
    Route::post('/admin/employees/{status}/{id}', [EmployeeController::class, 'toggleEmployeeStatus']);
    Route::put('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate']);
    Route::put('/employees/{id}/reactivate', [EmployeeController::class, 'reactivate']);
    Route::post('/users', [UserController::class, 'store']);
});
    Route::get('/user/profile', [UserController::class, 'fetchUserProfile']);
    Route::middleware('auth:sanctum')->put('/user/profile', [UserController::class, 'updateUserProfile']);
    Route::post('/user/upload-image', [UserController::class, 'uploadImage']);
    Route::middleware('auth:sanctum')->get('/user/profile', [UserController::class, 'profile']);
    Route::middleware('auth:sanctum')->put('/user/change-credentials', [UserController::class, 'changeCredentials']); // New route for changing username and password

// Common Routes for both Admin and User
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/documents/travel-order', [DocumentController::class, 'getTravelOrder']);
    Route::get('/documents/office-order', [DocumentController::class, 'getOfficeOrder']);
    Route::get('/admin/employees/{employee}/documents', [EmployeeDocumentController::class, 'index']);
    Route::post('/user/upload-image', [EmployeeController::class, 'uploadImage']);
});
