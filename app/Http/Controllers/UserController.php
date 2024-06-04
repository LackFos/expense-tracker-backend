<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\VerifyEmailOtpRequest;
use App\Models\OneTimePassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request) {
        
        try {
            DB::beginTransaction();
            
            $credentials = $request->validated();
            
            $user = User::create($credentials);
            
            $user['access_token'] = $user->createToken('access_token')->plainTextToken;
            
            EmailController::sendVerificationEmail($user->email);

            DB::commit();

            return Helpers::returnOkResponse('Account created', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return Helpers::throwInternalError($th);
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $isAuthenticated = Auth::attempt($credentials);

            
            if ($isAuthenticated) {
                /** @var User $user */
                $user = auth()->user();
                
                $user->currentAccessToken()?->delete();

                $token = $user->createToken('access_token')->plainTextToken;

                $user['access_token'] = $token;

                return Helpers::returnOkResponse('User logged in', $user);
            }

            return Helpers::throwNotFoundError('Invalid username or password');
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function verifyEmailOtp(VerifyEmailOtpRequest $request) {
        try {
            $validated = $request->validated();

            $user = $request->user;

            if($user->email_verified_at) {
                return Helpers::throwConflictError('User already verified');
            }    

            $oneTimePassword = OneTimePassword::where(['email' => $user->email, 'name' => 'verfiy_otp'])->first();
            
            if(!Hash::check($validated['otp'], $oneTimePassword->otp)) {
                return Helpers::throwUnauthorizedError('Invalid OTP');
            };
            
            $user->email_verified_at = now();
            $user->save();

            $oneTimePassword->delete();

            return Helpers::returnOkResponse('User verified');
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }
}