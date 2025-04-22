<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\ReferralSetting;
use Illuminate\Http\Request;

class ReferralSettingController extends Controller {
    public function index() {
        $pageTitle = 'Referral Setting';
        $levels    = ReferralSetting::all();
        return view('admin.referral.setting', compact('pageTitle', 'levels'));
    }

    public function save(Request $request) {
        $request->validate([
            'commission_count'      => 'required|integer',
            'commission'            => 'required|array',
            'commission.*.percent*' => 'required|numeric|gte:0',
        ]);

        $levels = [];
        $i=0;
        foreach ($request->commission as $commission) {
            $i++;
            $level = [
                'level' => $i,
                'percent' => $commission['percent']
            ];
            $levels[] = $level;
        }


        ReferralSetting::truncate();

        ReferralSetting::insert($levels);

        $general = GeneralSetting::first();
        $general->referral_commission_count = $request->commission_count;
        $general->save();


        $notify[] = ['success', 'Referral setting updated successfully'];
        return back()->withNotify($notify);
    }
}
