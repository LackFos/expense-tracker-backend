<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LedgerController;

Route::middleware('auth:sanctum')->prefix('ledgers')->group(function () {
    Route::get('/financial-report', [LedgerController::class, 'financialReport']);
    Route::get('/', [LedgerController::class, 'all']);
    Route::get('/{id}', [LedgerController::class, 'detail']);
    
    Route::post('/', [LedgerController::class, 'create']);
    Route::patch('/{id}', [LedgerController::class, 'update']);
    Route::delete('/{id}', [LedgerController::class, 'delete']);
});


Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});