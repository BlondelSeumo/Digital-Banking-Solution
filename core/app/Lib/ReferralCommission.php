<?php

namespace App\Lib;

use App\Models\ReferralSetting;
use App\Models\Transaction;

class ReferralCommission {

    public static function levelCommission($referee, $amount, $trx) {

        $general = gs();

        if (!$referee->referral_commission_count || !$general->modules->referral_system || !$referee->ref_by) {
            return;
        }

        $referee->referral_commission_count -= 1;
        $referee->save();

        $i           = 1;
        $settings    = ReferralSetting::all();
        $tempReferee = $referee;

        while ($i <= $settings->count()) {

            $referer = $tempReferee->referrer;

            if (!$referer) {
                break;
            }

            $commission = $settings->where('level', $i)->first();

            if (!$commission) {
                break;
            }

            $commissionAmount = ($amount * $commission->percent) / 100;
            $referer->balance += $commissionAmount;
            $referer->save();

            $transactions[] = [
                'user_id'      => $referer->id,
                'amount'       => $commissionAmount,
                'post_balance' => $referer->balance,
                'charge'       => 0,
                'trx_type'     => '+',
                'details'      => ordinal($i) . ' level referral commission from ' . $referee->username,
                'remark'       => 'referral_commission',
                'trx'          => $trx,
                'created_at'   => now(),
            ];

            notify($referer, 'REFERRAL_COMMISSION', [
                'amount'       => showAmount($commissionAmount,currencyFormat:false),
                'post_balance' => showAmount($referer->balance,currencyFormat:false),
                'trx'          => $trx,
                'level'        => ordinal($i),
            ]);

            $tempReferee = $referer;
            $i++;
        }

        if (isset($transactions)) {
            Transaction::insert($transactions);
        }
    }
}
