<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Branch;
use App\Models\Form;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {

    public function all() {
        $staff     = authStaff();
        $accounts  = User::query();
        $branchId  = session('branchId');
        $branches  = $staff->designation == Status::ROLE_MANAGER ? $staff->assignBranch : null;

        if ($staff->designation == Status::ROLE_MANAGER) {
            $branch = Branch::active()->findOrFail($branchId);
            $pageTitle = 'All Accounts Opened from ' . $branch->name;
        } else {
            $accounts = $accounts->where('branch_staff_id', $staff->id);
            $pageTitle = 'All Accounts Opened By ' . $staff->name;
        }

        if ($branchId) {
            $accounts = $accounts->where('branch_id', $branchId);
        }

        $accounts = $accounts
        ->searchable(['username', 'email', 'firstname', 'lastname'])
        ->with('branch:id,name', 'branchStaff:id,name')
        ->latest()
        ->paginate(getPaginate());

        return view('branch_staff.user.list', compact('pageTitle', 'accounts', 'staff', 'branches', 'branchId'));
    }

    public function find() {
        return $this->detail(request()->account_number);
    }

    public function detail($accountNumber) {
        $staff   = authStaff();
        $account = $accountNumber;
        $user    = User::where('username', $account)->orWhere('account_number', $account)->first();

        if (!$user) {
            $notify[] = ['error', 'Account not found'];
            return back()->withNotify($notify)->withInput();
        }

        $pageTitle = 'Account Details';
        return view('branch_staff.user.detail', compact('pageTitle', 'user', 'staff'));
    }

    public function open($account = null) {

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($account) {
            $account   = User::where('account_number', $account)->firstOrFail();
            $action    = route('staff.account.update', @$account->id);
            $pageTitle = 'Edit Account Details';
        } else {
            $pageTitle = 'Open New Account';
            $action    = route('staff.account.save');
        }

        return view('branch_staff.user.form', compact('pageTitle', 'countries', 'account', 'action'));
    }

    public function store(Request $request) {
        $this->validation($request);
        $kycData = null;
        if (gs('kv')) {
            $form              = Form::where('act', 'kyc')->first();
            $formData          = $form->form_data;
            $formProcessor     = new FormProcessor();
            $kycValidationRule = $formProcessor->valueValidation($formData);
            $request->validate($kycValidationRule);

            $kycData = $formProcessor->processFormData($request, $formData);
        }

        $password = getTrx(8);
        $user     = new User();

        if (gs('modules')->referral_system && $request->referrer) {

            $referrer = User::where('account_number', $request->referrer)->first();

            if (!$referrer) {
                $notify[] = ['error', 'Referrer account not found'];
                return back()->withNotify($notify)->withInput();
            }

            $user->ref_by                    = $referrer->id;
            $user->referral_commission_count = gs('referral_commission_count');
        }

        $user->password         = Hash::make($password);
        $user->kyc_data         = $kycData;
        $staff                  = authStaff();
        $branch                 = $staff->branch();
        $user->branch_id        = $branch->id;
        $user->branch_staff_id  = $staff->id;
        $user->account_number   = generateAccountNumber();
        $user->kv               = gs('kv') ? Status::NO : Status::YES;
        $user->ev               = gs('ev') ? Status::NO : Status::YES;
        $user->sv               = gs('sv') ? Status::NO : Status::YES;
        $user->status           = Status::USER_ACTIVE;
        $user->ts               = Status::DISABLE;
        $user->tv               = Status::VERIFIED;
        $user->profile_complete = 1;

        $user = $this->saveUser($request, $user);

        $adminNotification = new AdminNotification();

        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New account opened from ' . $branch->name;
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        notify($user, 'ACCOUNT_OPENED', [
            'email'    => $user->email,
            'username' => $user->username,
            'password' => $password,
        ]);

        $notify[] = ['success', 'Account opened successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $user      = User::where('branch_staff_id', authStaff()->id)->findOrFail($id);
        $oldEmail  = $user->email;
        $oldMobile = $user->mobile;
        $this->validation($request, $id);

        $user = $this->saveUser($request, $user);

        if ($oldEmail != $user->email) {
            $user->ev = 0;
            $user->save();
        }

        if ($oldMobile != $user->mobile) {
            $user->sv = 0;
            $user->save();
        }

        $notify[] = ['success', 'Account information updated successfully'];
        return back()->withNotify($notify);
    }

    protected function saveUser($request, $user) {
        $countryData = collect(json_decode(file_get_contents(resource_path('views/partials/country.json'))));
        $country     = $countryData[$request->country];
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = strtolower(trim($request->email));
        $user->username     = trim($request->username);
        $user->country_code = $request->country;
        $user->mobile       = $country->dial_code . $request->mobile;

        if ($request->hasFile('image')) {
            try {
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$country->country;
        $user->dial_code = $request->mobile_code;

        $user->save();
        return $user;
    }

    private function validation($request, $id = 0) {
        $countryData   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray  = (array) $countryData;
        $countries     = implode(',', array_keys($countryArray));
        $imgValidation = $id ? 'nullable' : 'required';

        $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'email'     => 'required|string|email|unique:users,email,' . $id,
            'mobile'    => 'required|regex:/^([0-9]*)$/',
            'username'  => 'required|min:6|unique:users,username,' . $id,
            'country'   => 'required|in:' . $countries,
            'image'     => [$imgValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'referrer'  => 'nullable|string',
        ]);

        if (preg_match('/[^a-z0-9_]/', trim($request->username))) {
            $notify[] = ['Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['No special character, space or capital letters in username.'];
            throw ValidationException::withMessages($notify);
        }

        $exist = User::where('mobile', $request->mobile_code . $request->mobile)->where('id', $id)->first();

        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }
    }
}
