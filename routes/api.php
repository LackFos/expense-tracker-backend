<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

Route::prefix('incomes')->group(function () {
    Route::get('/', [IncomeController::class, 'all']);
    Route::get('/{income}', [IncomeController::class, 'detail']);
    
    Route::post('/', [IncomeController::class, 'create']);
    Route::patch('/{income}', [IncomeController::class, 'update']);
    Route::delete('/{income}', [IncomeController::class, 'delete']);
});
