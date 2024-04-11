<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->prefix('incomes')->group(function () {
    Route::get('/', [IncomeController::class, 'all']);
    Route::get('/{id}', [IncomeController::class, 'detail']);
    
    Route::post('/', [IncomeController::class, 'create']);
    Route::patch('/{id}', [IncomeController::class, 'update']);
    Route::delete('/{id}', [IncomeController::class, 'delete']);
});

Route::middleware('auth:sanctum')->prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'all']);
    Route::get('/{id}', [ExpenseController::class, 'detail']);
    
    Route::post('/', [ExpenseController::class, 'create']);
    Route::patch('/{id}', [ExpenseController::class, 'update']);
    Route::delete('/{id}', [ExpenseController::class, 'delete']);
});

Route::prefix('users')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});