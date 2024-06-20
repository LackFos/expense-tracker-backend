<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\OneTimePasswordController;
use App\Http\Middleware\Verified;

Route::middleware(['auth:sanctum', Verified::class])->prefix('ledgers')->group(function () {
    Route::get('/financial-report', [LedgerController::class, 'financialReport']);
    Route::get('/', [LedgerController::class, 'all']);
    Route::get('/{id}', [LedgerController::class, 'detail']);
    
    Route::post('/', [LedgerController::class, 'create']);
    Route::put('/{id}', [LedgerController::class, 'update']);
    Route::delete('/{id}', [LedgerController::class, 'delete']);
});

Route::prefix('users')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/verify', [UserController::class, 'verifyAccount']);
    });
});

Route::prefix('otps')->group(function () {
    Route::post('/verify', [OneTimePasswordController::class, 'verify']);
});

Route::prefix('emails')->group(function () {
    Route::post('/reset-password-otp', [EmailController::class, 'createSendResetPasswordOTP']);
    Route::middleware(['auth:sanctum'])->post('/email-verification-otp', [EmailController::class, 'createSendVerificationOTP']);
});