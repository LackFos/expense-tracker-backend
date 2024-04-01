<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\IncomeRequest;

class IncomeController extends Controller
{
    public function all(Request $request)
    {
        $incomes = Income::all();
        return response()->json($incomes, 200);
    }

    public function detail(Income $income)
    {
        return response()->json($income, 200);
    }

    public function create(IncomeRequest $request)
    {
        $validated = $request->validated();
        $newIncome = Income::create($validated);
        return Helpers::returnCreatedResponse('Income created!', $newIncome);
    }

    public function update(IncomeRequest $request, Income $income)
    {
        $validated = $request->validated();
        $income->update($validated);
        return Helpers::returnOkResponse('Income Updated', $income);
    }


    public function delete(Income $income)
    {
        $income->delete();
        return Helpers::returnOkResponse('Income Deleted', $income);
    }
}
