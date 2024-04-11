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
        $expenses = Expense::all();
        return Helpers::returnOkResponse('Expenses Found', $expenses);
    }

    public function detail(Request $request, string $id)
    {
        $expense = Expense::find($id);
        
        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        return Helpers::returnOkResponse('Expense Found', $expense);
    }

    public function create(ExpenseRequest $request)
    {
        $validated = $request->validated();

        $newExpense = Expense::create($validated);
        
        return Helpers::returnCreatedResponse('Expense created', $newExpense);
    }

    public function update(ExpenseRequest $request, string $id)
    {
        $validated = $request->validated();

        $expense = Expense::find($id);
        
        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        $expense->update($validated);
        return Helpers::returnOkResponse('Expense Updated', $expense);
    }


    public function delete(Request $request, string $id)
    {
        $expense = Expense::find($id);

        if(!$expense) {
            return Helpers::throwNotFoundError('Expense not found');
        }

        $expense->delete();
        return Helpers::returnOkResponse('Expense Deleted', $expense);
    }
}
