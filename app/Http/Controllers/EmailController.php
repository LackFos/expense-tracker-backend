<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailVerificationOtp;
use App\Mail\EmailVerificationOtp as MailableEmailVerificationOtp;
use App\Models\OneTimePassword;

class EmailController extends Controller
{

    public static function sendVerificationEmail(string $email = null) {
        $user = auth()->user();

        if(!$user && $email) {
            $user = User::where('email', $email)->first();
        }
        
        if(!$user) {
            return Helpers::throwNotFoundError('User not found');
        }

        if($user->email_verified_at) {
            return Helpers::throwConflictError('User already verified');
        }

        $otp = strval(rand(100000, 999999));
        $username = $user->name;
        $emailAddress = $user->email;

        OneTimePassword::updateOrCreate(
            ['email' => $emailAddress, 'name' => 'verfiy_otp'],
            ['otp' => Hash::make($otp)]
        );

        Mail::to($emailAddress)->send(new MailableEmailVerificationOtp($otp, $username));

        return Helpers::returnOkResponse('OTP sent, please check your email');
    }
}
