<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

Route::prefix('incomes')->group(function () {
    Route::get('/', [IncomeController::class, 'all']);
    Route::get('/{id}', [IncomeController::class, 'detail']);
    
    Route::post('/', [IncomeController::class, 'create']);
    Route::patch('/{id}', [IncomeController::class, 'update']);
    Route::delete('/{id}', [IncomeController::class, 'delete']);
});

Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'all']);
    Route::get('/{id}', [ExpenseController::class, 'detail']);
    
    Route::post('/', [ExpenseController::class, 'create']);
    Route::patch('/{id}', [ExpenseController::class, 'update']);
    Route::delete('/{id}', [ExpenseController::class, 'delete']);
});
