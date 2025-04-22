<?php

namespace App\Http\Controllers\BranchStaff\Auth;

use App\Http\Controllers\Controller;
use App\Models\BranchStaff;
use App\Models\BranchStaffPasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/branch/staff/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('branch.staff.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $pageTitle  = "Account Recovery";
        $resetToken = BranchStaffPasswordReset::where('token', $token)->where('status', 0)->first();

        if (!$resetToken) {
            $notify[] = ['error', 'Verification code mismatch'];
            return to_route('staff.password.reset')->withNotify($notify);
        }
        $email = $resetToken->email;
        return view('branch_staff.auth.passwords.reset', compact('pageTitle', 'email', 'token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $reset = BranchStaffPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $staff = BranchStaff::where('email', $reset->email)->first();

        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return to_route('staff.login')->withNotify($notify);
        }

        $staff->password = Hash::make($request->password);
        $staff->save();

        $reset->status = 1;
        $reset->save();

        $staffIpInfo      = getIpInfo();
        $staffBrowserInfo = osBrowser();

        $staff->username = $staff->name;

        notify($staff, 'PASS_RESET_DONE', [
            'operating_system' => $staffBrowserInfo['os_platform'],
            'browser'          => $staffBrowserInfo['browser'],
            'ip'               => $staffIpInfo['ip'],
            'time'             => $staffIpInfo['time'],
        ], ['email'], false);

        $notify[] = ['success', 'Password changed'];
        return to_route('staff.login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('branch_staff');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('branch_staff');
    }
}
