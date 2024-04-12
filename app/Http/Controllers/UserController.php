<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Income;
use App\Models\Expense;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(UserRequest $request) {
        $credential = $request->validated();

        if(Auth::attempt($credential)) {
            $user = $request->user();
            $user['access_token'] = $request->user()->createToken('access_token')->plainTextToken;
            return Helpers::returnOkResponse('User logged in successfully', $user);
        }
        
        return Helpers::throwUnauthorizedError('Invalid username or password');
    }

    public function financialReport(Request $request) {
        $userId = auth()->id();

        $currentMonthStart = Carbon::now()->startOfMonth(); 
        $currentMonthEnd =  Carbon::now()->endOfMonth();
        
        $currentMonthIncome = (int) Income::where('user_id', $userId)
                                        ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                                        ->sum('amount');
        
        $currentMonthExpense = (int) Expense::where('user_id', $userId)
                                        ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                                        ->sum('amount');

        $totalIncome = (int) Income::where('user_id', $userId)->sum('amount');
        $totalExpense = (int) Expense::where('user_id', $userId)->sum('amount');
        $remainingMoney = $totalIncome - $totalExpense;

        return Helpers::returnOkResponse('User found', compact('currentMonthIncome', 'currentMonthExpense', 'totalIncome', 'totalExpense', 'remainingMoney'));
    }
}