<?php

namespace App\Http\Controllers\BranchStaff\Auth;

use App\Http\Controllers\Controller;
use App\Models\BranchStaff;
use App\Models\BranchStaffPasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('branch.staff.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm() {
        $pageTitle = 'Account Recovery';
        return view('branch_staff.auth.passwords.email', compact('pageTitle'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker() {
        return Password::broker('branch_staff');
    }

    public function sendResetCodeEmail(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:branch_staff,email',
        ]);

        $staff = BranchStaff::where('email', $request->email)->first();

        $code = verificationCode(6);

        $staffPassReset        = new BranchStaffPasswordReset();
        $staffPassReset->email = $staff->email;
        $staffPassReset->token = $code;
        $staffPassReset->save();

        $staffIpInfo      = getIpInfo();
        $staffBrowserInfo = osBrowser();

        $staff->username = $staff->name;

        notify($staff, 'PASS_RESET_CODE', [
            'code'             => $code,
            'operating_system' => $staffBrowserInfo['os_platform'],
            'browser'          => $staffBrowserInfo['browser'],
            'ip'               => $staffIpInfo['ip'],
            'time'             => $staffIpInfo['time'],
        ], ['email'], false);

        $email = $staff->email;
        session()->put('pass_res_mail', $email);

        return to_route('staff.password.code.verify');
    }

    public function codeVerify() {
        $pageTitle = 'Verify Code';
        $email     = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Oops! session expired'];
            return to_route('staff.password.reset')->withNotify($notify);
        }
        return view('branch_staff.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request) {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $notify[] = ['success', 'You can change your password.'];
        $code     = str_replace(' ', '', $request->code);

        return to_route('staff.password.reset.form', $code)->withNotify($notify);
    }
}
