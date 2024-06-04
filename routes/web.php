<?php

use Illuminate\Http\Request;
use App\Mail\EmailVerificationOTP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Laravel Framework 11.1.1';
});

Route::get('/a', function () {
    return view('mail.email-verification-otp', ['username' => 'Fariz Hitam', 'otp' => '123456']);
});
