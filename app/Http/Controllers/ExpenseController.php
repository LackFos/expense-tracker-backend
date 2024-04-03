<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\IncomeRequest;

class ExpenseController extends Controller
{
    public function all(Request $request)
    {
        $incomes = Income::all();
        return response()->json($incomes, 200);
    }

    public function detail(Request $request, string $id)
    {
        $income = Income::find($id);
        
        if(!$income) {
            return Helpers::returnNotFoundError('Income not found');
        }

        return response()->json($income, 200);
    }

    public function create(IncomeRequest $request)
    {
        $validated = $request->validated();

        $newIncome = Income::create($validated);
        
        return Helpers::returnCreatedResponse('Income created!', $newIncome);
    }

    public function update(IncomeRequest $request, Income $income, string $id)
    {
        $validated = $request->validated();

        $income = Income::find($id);
        
        if(!$income) {
            return Helpers::returnNotFoundError('Income not found');
        }

        $income->update($validated);
        return Helpers::returnOkResponse('Income Updated', $income);
    }


    public function delete(Request $request, string $id)
    {
        $income = Income::find($id);

        if(!$income) {
            return Helpers::returnNotFoundError('Income not found');
        }

        $income->delete();
        return Helpers::returnOkResponse('Income Deleted', $income);
    }
}
