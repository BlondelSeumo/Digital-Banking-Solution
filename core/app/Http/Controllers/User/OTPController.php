<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\OtpVerification;
use Illuminate\Http\Request;

class OTPController extends Controller {
    public function verifyOtp() {
        $pageTitle    = 'OTP Verification';
        $verification = OtpVerification::findOrFail(sessionVerificationId());

        if ($verification->used_at) {
            return to_route('user.home');
        }

        return view('Template::user.otp.verify', compact('pageTitle', 'verification'));
    }

    public function submitOTP(Request $request, $id = 0) {
        $request->validate(['otp' => 'required|digits:6']);

        $verification = OtpVerification::find($id);

        $otpManager               = new OTPManager();
        $otpManager->verification = $verification;
        $otpManager->checkOTP($request->otp);

        $verification->used_at = now();

        if ($verification->send_via == '2fa') {
            $verification->otp        = $request->otp;
            $verification->expired_at = now();
        }

        $verification->save();
        return to_route($verification->additional_data->after_verified);
    }

    public function resendOtp($id) {
        $verification = OtpVerification::find($id);

        if (!$verification) {
            return to_route('user.home');
        }

        if ($verification->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $otpManager               = new OTPManager();
        $otpManager->verification = $verification;
        $otpManager->renewOTP();

        return to_route('user.otp.verify');
    }
}
