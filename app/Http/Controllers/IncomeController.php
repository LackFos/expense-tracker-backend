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
        $userId = auth()->id();
        $incomes = Income::where('user_id', $userId)->get();

        if($incomes->isEmpty()) {
            return Helpers::throwNotFoundError('Incomes Not Found');
        }

        return Helpers::returnOkResponse('Incomes Found', $incomes);
    }

    public function detail(Request $request, string $id)
    {
        $userId = auth()->id();
        $income = Income::where(['id' => $id, 'user_id' => $userId])->first();
        
        if(!$income) {
            return Helpers::throwNotFoundError('Income not found');
        }

        return Helpers::returnOkResponse('Income Found', $income);
    }

    public function create(IncomeRequest $request)
    {
        $validated = $request->validatedWithUser();

        $newIncome = Income::create($validated);
        
        return Helpers::returnCreatedResponse('Income created', $newIncome);
    }

    public function update(IncomeRequest $request, Income $income, string $id)
    {
        $validated = $request->validated();
       
        $userId = auth()->id();
        $income = Income::where(['id' => $id, 'user_id' => $userId])->first();
        
        if(!$income) {
            return Helpers::throwNotFoundError('Income not found');
        }

        $income->update($validated);
        return Helpers::returnOkResponse('Income Updated', $income);
    }


    public function delete(Request $request, string $id)
    {
        $userId = auth()->id();
        $income = Income::where(['id' => $id, 'user_id' => $userId])->first();

        if(!$income) {
            return Helpers::throwNotFoundError('Income not found');
        }

        $income->delete();
        return Helpers::returnOkResponse('Income Deleted', $income);
    }
}
