<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Lib\Reloadly;
use App\Models\Country;
use App\Models\Operator;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirtimeController extends Controller
{
    public function countries()
    {
        $countries = Country::active()->whereHas('operators', function ($query) {
            $query->active();
        })->orderBy('name')->get();

        $notify[] = 'Airtime Countries';
        return response()->json([
            'remark'  => 'airtime_countries',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'countries' => $countries,
            ],
        ]);
    }
    public function operators($countryId)
    {

        $country = Country::where('id', $countryId)->active()->first();
        if (!$country) {
            $notify[] = 'Invalid country id';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $operators = Operator::active()->where('country_id', $country->id)->orderBy('name')->get();

        $notify[]    = 'Operators';
        $tabs        = [];
        $tabs['all'] = $operators;

        $recharges   = $operators->filter(function ($operator) {
            return $operator->bundle == Status::NO && $operator->data == Status::NO && $operator->pin == Status::NO;
        })->values();

        $bundles   = $operators->filter(function ($operator) {
            return $operator->bundle == Status::ENABLE;
        })->values();

        $data   = $operators->filter(function ($operator) {
            return $operator->data == Status::ENABLE;
        })->values();

        $pin  = $operators->filter(function ($operator) {
            return $operator->pin == Status::ENABLE;
        })->values();

        if (count($recharges)) $tabs['recharge'] = $recharges;
        if (count($bundles)) $tabs['bundle']     = $bundles;
        if (count($data)) $tabs['data']          = $data;
        if (count($pin)) $tabs['pin']            = $pin;

        return response()->json([
            'remark'  => 'operators',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'tabs' => $tabs
            ],
        ]);
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

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $operator = Operator::active()->find($request->operator_id);

        if (!$operator) {
            $notify[] = 'Invalid operator selected';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $response = $this->topUpValidation($request, $operator);

        if (!$response['status']) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => @$response['message']],
            ]);
        }

        if ($request->amount > auth()->user()->balance) {
            $notify[] = 'Insufficient balance';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $additionalData = [
            'after_verified'    => 'api.airtime.top.up',
            'country_id'        => $request->country_id,
            'operator_id'       => $request->operator_id,
            'calling_code'      => $request->calling_code,
            'mobile_number'     => $request->mobile_number,
            'amount'            => $request->amount
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($operator, $request->auth_mode, 'AIRTIME_OTP', $additionalData, true);
    }

    public function topUp($id)
    {
        $verification = OtpVerification::find($id);

        if (!$verification) {
            $notify[] = 'Invalid OTP provided';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $validator = Validator::make(request()->all(), []);
        OTPManager::checkVerificationData($verification, Operator::class, true, $validator);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = auth()->user();

        $operator = $verification->verifiable;
        $amount = $verification->additional_data->amount;

        $callingCode  = $verification->additional_data->calling_code;
        $mobileNumber = $verification->additional_data->mobile_number;

        $country = Country::active()->find($verification->additional_data->country_id);

        if (!$country) {
            $notify[] = 'Country not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
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

            $notify[] = 'Top-Up completed successfully';
            return response()->json([
                'remark'  => 'airtime_top_up',
                'status'  => 'success',
                'message' => ['success' => $notify],
            ]);
        } else {
            $notify[] = @$response['message'];
            return response()->json([
                'remark'  => 'api_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
    }

    private function topUpValidation($request, $operator)
    {
        if ($operator->denomination_type == 'FIXED') {
            if (!in_array($request->amount, $operator->fixed_amounts)) {
                return [
                    'status'  => false,
                    'message' => 'Invalid amount selected'
                ];
            }
        } else {
            $minAmount = $operator->min_amount;
            $maxAmount = $operator->max_amount;

            if ($request->amount < $minAmount) {
                return [
                    'status'  => false,
                    'message' => 'Amount should be greater than ' . $minAmount . ' ' . gs('cur_text')
                ];
            }

            if ($request->amount > $maxAmount) {
                return [
                    'status'  => false,
                    'message' => 'Amount should be less than ' . $maxAmount . ' ' . gs('cur_text')
                ];
            }
        }

        return [
            'status' => true
        ];
    }
}
