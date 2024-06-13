<?php

namespace App\Http\Controllers;

use App\Enums\OtpType;
use App\Models\User;
use App\Http\Helpers;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Mail\SendOtpEmail;
use App\Mail\VerificationOtpEmail;
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
            
            $otp = $user->generateOTP(OtpType::VERIFY_ACCOUNT);
            
            $user->sendEmail(new SendOtpEmail('Verifikasi Akun - Kode OTP',  $user->name, $otp));
            
            DB::commit();

            return Helpers::returnOkResponse('Account created', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return Helpers::throwInternalError($th->getMessage());
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $isAuthenticated = Auth::attempt($credentials);
            
            if (!$isAuthenticated) {
                return Helpers::throwNotFoundError('Invalid username or password');
            }
                    
            /** @var User $user */
            $user = auth()->user();
                
            $user->currentAccessToken()?->delete();

            $token = $user->createToken('access_token')->plainTextToken;

            $user['access_token'] = $token;

            return Helpers::returnOkResponse('User logged in', $user);
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public function verifyAccount(VerifyAccountRequest $request) {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $user = $request->user;

            $otp = $user->getVerifyAcountOTP();
            
            if($user->email_verified_at) {
                return Helpers::throwConflictError('User already verified');
            }    

            if(!$otp) {
                return Helpers::throwNotFoundError('OTP not found or expired, please request again');
            }

            if(!Hash::check($validated['otp'], $otp->token)) {
                return Helpers::throwUnauthorizedError('Invalid OTP');
            }

            $user->email_verified_at = now();
            $user->save();
            $otp->delete();

            DB::commit();

            return Helpers::returnOkResponse('User verified', $otp);
        } catch (\Throwable $th) {
            DB::rollBack();
            return Helpers::throwInternalError($th);
        }
    }

    public function resetPassword(ResetPasswordRequest $request) {
        try{
            DB::beginTransaction();

            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();

            $otp = $user->getResetPasswordOTP();

            if(!$otp) {
                return Helpers::throwConflictError('OTP expired, please request again');
            }    

            if(!Hash::check($validated['otp'], $otp->token)) {
                return Helpers::throwUnauthorizedError('Invalid OTP');
            }

            $user->password = Hash::make($validated['password']);
            $user->save();
            $otp->delete();

            DB::commit();

            return Helpers::returnOkResponse('Password reset successfuly', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return Helpers::throwInternalError($th);
        }
    }
}