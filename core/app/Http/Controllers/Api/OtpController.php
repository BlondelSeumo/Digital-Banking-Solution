<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller {
    public function submitOTP(Request $request, $id = 0) {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $verification = OtpVerification::find($id);
        if (!$verification) {
            $notify[] = 'OTP verification not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $otpManager               = new OTPManager();
        $otpManager->verification = $verification;
        $otpManager->checkOTP($request->otp, true, $validator);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $verification->used_at = now();

        if ($verification->send_via == '2fa') {
            $verification->otp        = $request->otp;
            $verification->expired_at = now();
        }

        $verification->save();
        $notify[] = 'OTP submitted successfully';
        return callApiMethod($verification->additional_data->after_verified, $verification->id);
    }

    public function resendOtp($id) {
        $verification = OtpVerification::find($id);

        if (!$verification) {
            $notify[] = 'Otp verification not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($verification->user_id != auth()->id()) {
            $notify[] = 'Unauthorized Action';
            return response()->json([
                'remark'  => 'unauthorized',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $otpManager               = new OTPManager();
        $otpManager->verification = $verification;
        $otpManager->renewOTP(true);

        $notify[] = 'OTP resend successfully';
        return response()->json([
            'remark'  => 'resend_otp',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
