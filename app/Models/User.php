<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\OtpType;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    public function incomes()
    {
        return $this->hasMany(Ledger::class)->where('type', 'income');
    }

    public function expenses()
    {
        return $this->hasMany(Ledger::class)->where('type', 'expense');
    }

    public function currentAccessToken(): PersonalAccessToken | null
    {
        return $this->hasOne(PersonalAccessToken::class, 'tokenable_id', 'id')->where('name', 'access_token')->first();
    }

    private function oneTimePasswords() {
        return $this->hasMany(OneTimePassword::class, 'email', 'email');
    }

    public function getVerifyAcountOTP() {
        return $this->oneTimePasswords()                
            ->where('name', OtpType::VERIFY_ACCOUNT)
            ->where('expire_at', '>', Carbon::now())
            ->first();
    }

    public function getResetPasswordOTP() {
        return $this->oneTimePasswords()
            ->where('name', OtpType::RESET_PASSWORD)
            ->where('expire_at', '>', Carbon::now())
            ->first();
    }

    public function generateOTP(OtpType $type): ?string 
    {
        $otp = OneTimePassword::where([
            'email' => $this->email,
            'name' => $type,
        ])->first();

        if ($otp && !$otp->isExpired()) {
            return null;
        }

        $newOTP = strval(rand(100000, 999999));
        
        OneTimePassword::updateOrCreate(
            [
                'email' => $this->email,
                'name' => $type
            ],
            [
                'token' => Hash::make($newOTP),
                'expire_at' => Carbon::now()->addMinutes(3)
            ]
        );
        
        return $newOTP;
    }

    public function sendEmail(Mailable $mailable) {
        Mail::to($this->email)->send($mailable);
    }
}