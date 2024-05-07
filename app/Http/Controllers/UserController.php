<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(UserRequest $request) {
        try {
            $credentials = $request->validated();
        
            $user = User::create($credentials);
    
            $user['access_token'] = $user->createToken('access_token')->plainTextToken;
    
            return Helpers::returnOkResponse('Account created', $user);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function login(UserRequest $request) {
        try {
            $credentials = $request->validated();

            if(Auth::attempt($credentials)) {
                $user = auth()->user();
                $user['access_token'] = $request->user()->createToken('access_token')->plainTextToken;
                return Helpers::returnOkResponse('User logged in', $user);
            }
            
            return Helpers::throwNotFoundError('Invalid username or password');
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }
}