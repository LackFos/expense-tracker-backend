<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\LedgerCreateRequest;
use App\Http\Requests\LedgerUpdateRequest;

class LedgerController extends Controller
{
    public function financialReport(Request $request) {
        try {
            $user = auth()->user();

            $currentMonthStart = Carbon::now()->startOfMonth(); 
            $currentMonthEnd =  Carbon::now()->endOfMonth();
            
            $currentMonthIncome = (int) $user->incomes()
                                            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                                            ->sum('amount');
            
            $currentMonthExpense = (int) $user->expenses()
                                            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                                            ->sum('amount');
    
            $totalIncome = (int) $user->incomes()->sum('amount');
            $totalExpense = (int) $user->expenses()->sum('amount');
            $remainingMoney = $totalIncome - $totalExpense;
    
            return Helpers::returnOkResponse(
                'User found', 
                compact(
                    'currentMonthIncome',
                    'currentMonthExpense',
                    'totalIncome',
                    'totalExpense',
                    'remainingMoney'
                )
            );
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function all(Request $request) 
    {
        try {
            $user = auth()->user();

            $query = $user->ledgers()->orderByDesc('date');
    
            if($request->query('search')) {
                $query = $query->where('description', 'like', '%' . $request->query('search') . '%');
            }

            if($request->query('type')) {
                $query = $query->where('type', $request->query('type'));
            }

            if($request->query('startDate')) {
                $startDate = $request->query('startDate');
                
                if($request->query('endDate')) {
                    $endDate = $request->query('endDate');
                    $query = $query->whereBetween('date', [$startDate, $endDate]);
                } else {
                    $query = $query->where('date', $startDate);
                }
            }
 
            $ledgers = $query->get();

            if($ledgers->isEmpty()) {
                return Helpers::throwNotFoundError('Transaction not found');
            }

            return Helpers::returnOkResponse('Transaction found', $ledgers);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function detail(string $id)
    {
        try {
            $user = auth()->user();

            $transaction = $user->ledgers->where('id', $id)->first();
            
            if(!$transaction) {
                return Helpers::throwNotFoundError('Transaction not found');
            }

            return Helpers::returnOkResponse('Transaction found', $transaction);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function create(LedgerCreateRequest $request)
    {
       try {
            $validated = $request->validated();

            $user = auth()->user();

            $transaction = $user->ledgers()->create($validated);
            
            return Helpers::returnCreatedResponse('Transaction created', $transaction);
       } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function update(LedgerUpdateRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $user = auth()->user();
    
            $transaction = $user->ledgers()->where('id', $id)->first();
            
            if($transaction->isEmpty()) {
                return Helpers::throwNotFoundError('Transaction not found');
            }
    
            $transaction->update($validated);
    
            return Helpers::returnOkResponse('Transaction updated', $transaction);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }


    public function delete(string $id)
    {
        try {
            $user = auth()->user();

            $transaction = $user->ledgers()->where('id', $id)->first();
    
            if($transaction->isEmpty()) {
                return Helpers::throwNotFoundError('Transaction not found');
            }
    
            $transaction->delete();
            
            return Helpers::returnOkResponse('Transaction deleted', $transaction);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }
}
