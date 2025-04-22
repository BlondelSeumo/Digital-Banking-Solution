<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Lib\Reloadly;
use App\Models\Country;
use App\Models\Operator;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AirtimeController extends Controller
{
    public function form()
    {
        $pageTitle = 'Mobile Top Up';
        $countries = Country::active()->whereHas('operators', function ($query) {
            $query->active();
        })->get();

        return view('Template::user.airtime.form', compact('pageTitle', 'countries'));
    }

    public function apply(Request $request)
    {
        $rules = [
            'country_id'       => 'required|integer',
            'operator_id'      => 'required|integer',
            'calling_code'     => 'required|string',
            'mobile_number'    => 'required|numeric',
            'amount'           => 'required|numeric|gt:0'
        ];

        $rules = mergeOtpField($rules);
        $request->validate($rules);

        $operator = Operator::active()->find($request->operator_id);

        if (!$operator) {
            $notify[] = ['error', 'Invalid operator selected'];
            return back()->withNotify($notify);
        }

        $this->topUpValidation($request, $operator);

        if ($request->amount > auth()->user()->balance) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }

        $additionalData = [
            'after_verified'    => 'user.airtime.top.up',
            'country_id'        => $request->country_id,
            'operator_id'       => $request->operator_id,
            'calling_code'      => $request->calling_code,
            'mobile_number'     => $request->mobile_number,
            'amount'            => $request->amount
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($operator, $request->auth_mode, 'AIRTIME_OTP', $additionalData);
    }

    public function topUp()
    {
        $verification = OtpVerification::find(sessionVerificationId());
        OTPManager::checkVerificationData($verification, Operator::class);

        $user = auth()->user();

        $operator = $verification->verifiable;
        $amount = $verification->additional_data->amount;

        $callingCode  = $verification->additional_data->calling_code;
        $mobileNumber = $verification->additional_data->mobile_number;

        $country = Country::active()->find($verification->additional_data->country_id);



        if (!$country) {
            $notify[] = ['error', 'Country not found'];
            return to_route('user.airtime.form')->withNotify($notify);
        }

        $recipient['number'] = $mobileNumber;
        $recipient['countryCode'] = $country->iso_name;

        $reloadly = new Reloadly();
        $reloadly->operatorId = $operator->unique_id;

        $response = $reloadly->topUp($verification->additional_data->amount, $recipient);

        if ($response['status']) {
            $user->balance -= $amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->charge = 0;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type = '-';
            $transaction->trx = $response['custom_identifier'] ?? getTrx();
            $transaction->details = 'Top-up ' . $amount . ' ' . gs('cur_text') . ' to ' . $callingCode . $mobileNumber;
            $transaction->remark = 'top_up';
            $transaction->save();

            notify($user, 'TOP_UP', [
                'amount'        => showAmount($amount, currencyFormat:false),
                'mobile_number' => $callingCode . $mobileNumber,
                'post_balance'  => showAmount($user->balance, currencyFormat:false)
            ]);

            $notify[] = ['success', 'Top-Up completed successfully'];
            return to_route('user.airtime.form')->withNotify($notify);
        } else {
            $notify[] = ['error', @$response['message']];
            return to_route('user.airtime.form')->withNotify($notify);
        }
    }

    private function topUpValidation($request, $operator)
    {
        if ($operator->denomination_type == 'FIXED') {
            if (!in_array($request->amount, $operator->fixed_amounts)) {
                throw ValidationException::withMessages(['error' => 'Invalid amount selected']);
            }
        } else {
            $minAmount = $operator->min_amount;
            $maxAmount = $operator->max_amount;

            if ($request->amount < $minAmount) {
                throw ValidationException::withMessages(['error' => 'Amount should be greater than ' . $minAmount . ' ' . gs('cur_text')]);
            }

            if ($request->amount > $maxAmount) {
                throw ValidationException::withMessages(['error' => 'Amount should be less than ' . $maxAmount . ' ' . gs('cur_text')]);
            }
        }
    }

    public function getOperatorByCountry($id)
    {
        $status = true;
        $operators = Operator::active()->where('country_id', $id)->get();

        if (!$operators->count()) {
            $status = false;
        }

        return response()->json([
            'status' => $status,
            'operators' => $operators
        ]);
    }
}
