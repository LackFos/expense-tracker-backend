<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\User;
use App\Enums\OtpType;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\SendOtpEmail;
use App\Mail\VerificationOtpEmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public static function createSendVerificationOTP(Request $request) {
        try {
            /** @var User $user */
            $user = auth()->user();

            if(!$user) {
                return Helpers::throwNotFoundError('User not found');
            }

            if($user->email_verified_at) {
                return Helpers::throwConflictError('User already verified');
            }

            $otp = $user->generateOTP(OtpType::VERIFY_ACCOUNT);

            if(!$otp) {
                return Helpers::throwTooManyRequest('You have already requested an OTP recently. Please try again in next 3 minutes');
            }

            $user->sendEmail(new SendOtpEmail('Verifikasi Akun - Kode OTP',  $user->name, $otp));

            return Helpers::returnOkResponse('OTP sent, please check your email');  
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }

    public static function createSendResetPasswordOTP(ForgotPasswordRequest $request) {
        try {
            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();

            if(!$user) {
                return Helpers::throwNotFoundError('User not found');
            }
            
            $otp = $user->generateOTP(OtpType::RESET_PASSWORD);

            if(!$otp) {
                return Helpers::throwTooManyRequest('You have already requested an OTP recently. Please try again in next 3 minutes');
            }

            $user->sendEmail(new SendOtpEmail('Lupa Password - Kode OTP',  $user->name, $otp));

            return Helpers::returnOkResponse('OTP sent, please check your email');
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }
}
