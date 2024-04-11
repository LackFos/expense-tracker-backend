<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Http\Requests\UserRequest;
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
 
        return response()->json('failed', 200);
    }
}
