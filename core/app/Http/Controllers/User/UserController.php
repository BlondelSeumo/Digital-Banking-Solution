<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\BalanceTransfer;
use App\Models\Deposit;
use App\Models\DeviceToken;
use App\Models\Dps;
use App\Models\Fdr;
use App\Models\Form;
use App\Models\Loan;
use App\Models\ReferralSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller {
    public function home() {
        $pageTitle = 'Dashboard';
        $user                     = auth()->user();
        $widget['total_deposit']  = Deposit::pending()->where('user_id', $user->id)->sum('amount');
        $widget['total_withdraw'] = Withdrawal::pending()->where('user_id', $user->id)->sum('amount');
        $widget['total_trx']      = Transaction::where('user_id', $user->id)->whereDate('created_at', now()->today())->count();
        $widget['total_fdr']      = Fdr::running()->where('user_id', $user->id)->count();
        $widget['total_loan']     = Loan::running()->where('user_id', $user->id)->count();
        $widget['total_dps']      = Dps::running()->where('user_id', $user->id)->count();

        $credits = Transaction::where('user_id', $user->id)->where('trx_type', '+')->latest()->limit(5)->get();
        $debits  = Transaction::where('user_id', $user->id)->where('trx_type', '-')->latest()->limit(5)->get();
        return view('Template::user.dashboard', compact('pageTitle', 'user', 'credits', 'debits', 'widget'));
    }

    public function depositHistory(Request $request) {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit.index', compact('pageTitle', 'deposits'));
    }

    public function details($trxNumber) {
        $pageTitle = 'Deposit Details';
        $deposit = auth()->user()->deposits()->where('trx', $trxNumber)->with(['gateway'])->orderBy('id', 'desc')->firstOrFail();
        return view('Template::user.deposit.details', compact('pageTitle', 'deposit'));
    }

    public function show2faForm() {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request) {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions() {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        if (request()->today) {
            request()->merge(['date' => now()->today()->format('F d, Y')]);
        }

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm() {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData() {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request) {
        $form = Form::where('act', 'kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);
        $user->kyc_data = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function userData() {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'Complete Your Profile';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request) {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'username'     => 'required|unique:users|min:6',
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
            'image'        => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'address'      => 'nullable|string',
            'state'        => 'nullable|string',
            'city'         => 'nullable|string',
            'zip'          => 'nullable|string',

        ]);

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;


        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }


    public function addDeviceToken(Request $request) {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash) {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function referredUsers() {
        $pageTitle = "My referred Users";
        $user      = auth()->user();
        $referees  = User::where('ref_by', $user->id)->with('allReferees')->paginate(getPaginate());
        $maxLevel  = ReferralSetting::max('level');
        return view('Template::user.referral.index', compact('pageTitle', 'referees', 'user', 'maxLevel'));
    }

    public function transferHistory() {
        $pageTitle = 'Transfer History';
        $transfers = BalanceTransfer::where('user_id', auth()->id())->searchable(['trx', 'beneficiary:account_number'])->dateFilter()->with('beneficiary', 'beneficiary.beneficiaryOf');
        if (request()->download == 'pdf') {
            $transfers = $transfers->get();
            return downloadPDF('Template::pdf.transfer_list', compact('pageTitle', 'transfers'));
        }
        $transfers = $transfers->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('Template::user.transfer.history', compact('pageTitle', 'transfers'));
    }

    public function transferDetails($trxNumber) {
        $transfer = auth()->user()->transfer()->where('trx', $trxNumber)->with(['user', 'beneficiary'])->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle    = "Transfer Information";
        if (request()->has('download')) {
            return downloadPDF('pdf.transfer_details', compact('pageTitle', 'transfer'));
        }
        return view('Template::user.transfer.details', compact('pageTitle', 'transfer'));
    }
}
