<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BeneficiaryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public routes (Authentication)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // User Management
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::post('/profile-picture', [UserController::class, 'uploadProfilePicture']);
        Route::post('/transaction-pin', [UserController::class, 'createTransactionPin']);
        Route::put('/transaction-pin', [UserController::class, 'updateTransactionPin']);
        Route::post('/next-of-kin', [UserController::class, 'addNextOfKin']);
    });
    
    // Beneficiaries
    Route::prefix('beneficiaries')->group(function () {
        Route::get('/', [BeneficiaryController::class, 'index']);
        Route::post('/', [BeneficiaryController::class, 'store']);
        Route::put('/{id}', [BeneficiaryController::class, 'update']);
        Route::delete('/{id}', [BeneficiaryController::class, 'destroy']);
    });
    
    // Transactions
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/deposit', [TransactionController::class, 'deposit']);
        Route::post('/transfer', [TransactionController::class, 'transfer']);
    });
    
    // Dashboard
    Route::get('/dashboard/{userId}', [DashboardController::class, 'show']);
});
