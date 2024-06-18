<?php

namespace App\Http\Controllers;

use App\Enums\OtpType;
use App\Http\Helpers;
use App\Http\Requests\OneTimePasswordVerifyRequest;
use App\Models\OneTimePassword;
use Illuminate\Support\Facades\Hash;

class OneTimePasswordController extends Controller
{
    public function verify(OneTimePasswordVerifyRequest $request)
    {
        try {
            $validated = $request->validated();

            $otp = OneTimePassword::where(['email' => $validated['email'], 'name' => $validated['name']])->first();

            if(!$otp || !Hash::check($validated['otp'], $otp->token)) {
                return Helpers::throwUnauthorizedError('Invalid OTP');
            }
        
            return Helpers::returnOkResponse('OTP Valid');
        } catch (\Throwable $th) {
            return Helpers::throwInternalError($th);
        }
    }
}
