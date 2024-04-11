<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Expense;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExpenseController extends Controller
{
    public function all(Request $request)
    {
        $userId = auth()->id();
        $expenses = Expense::where('user_id', $userId)->get();

        if($expenses->isEmpty()) {
            return Helpers::throwNotFoundError('Expenses Not Found');
        }

        return Helpers::returnOkResponse('Expenses Found', $expenses);
    }

    public function detail(Request $request, string $id)
    {
        $userId = auth()->id();
        $expense = Expense::where(['id' => $id, 'user_id' => $userId])->first();
        
        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        return Helpers::returnOkResponse('Expense Found', $expense);
    }

    public function create(ExpenseRequest $request)
    {
        $validated = $request->validatedWithUser();

        $newExpense = Expense::create($validated);
        
        return Helpers::returnCreatedResponse('Expense created', $newExpense);
    }

    public function update(ExpenseRequest $request, string $id)
    {
        $validated = $request->validated();

        $userId = auth()->id();
        $expense = Expense::where(['id' => $id, 'user_id' => $userId])->first();
        
        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        $expense->update($validated);
        return Helpers::returnOkResponse('Expense Updated', $expense);
    }


    public function delete(Request $request, string $id)
    {
        $userId = auth()->id();
        $expense = Expense::where(['id' => $id, 'user_id' => $userId])->first();

        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        $expense->delete();
        return Helpers::returnOkResponse('Expense Deleted', $expense);
    }
}
