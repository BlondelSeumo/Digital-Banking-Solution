<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WireTransferSetting;

class WireTransferSettingController extends Controller {

    public function form() {
        $pageTitle = 'Wire Transfer Form';
        $form      = Form::where('act', 'wire_transfer')->first() ?? new Form();
        $form->mergeDefaultTransferFields();
        return view('admin.wire_transfer.form', compact('pageTitle', 'form'));
    }

    public function saveForm(Request $request) {
        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);
        $exist = Form::where('act', 'wire_transfer')->exists();
        $formProcessor->generate('wire_transfer', $exist, 'act');
        $notify[] = ['success', 'Wire transfer form saved successfully'];
        return back()->withNotify($notify);
    }

    public function setting() {
        $pageTitle = 'Wire Transfer Setting';
        $setting = WireTransferSetting::first();
        return view('admin.wire_transfer.setting', compact('pageTitle', 'setting'));
    }

    public function saveSetting(Request $request) {
        $request->validate([
            'minimum_limit'            => 'required|numeric|gt:0',
            'maximum_limit'            => 'required|numeric|gt:minimum_limit',
            'daily_maximum_limit'      => 'required|numeric|gte:maximum_limit',
            'monthly_maximum_limit'    => 'required|numeric|gte:daily_maximum_limit',
            'daily_total_transaction'   => 'required|integer|gte:0',
            'monthly_total_transaction' => 'required|integer|gte:daily_total_transaction',
            'fixed_charge'              => 'required|numeric|gte:0',
            'percent_charge'            => 'required|numeric|gte:0',
            'instruction'               => 'nullable|max:64000',
        ], [
            'maximum_limit.gt'             => 'Maximum transfer limit must be greater than minimum transfer limit',
            'daily_maximum_limit.gte'      => 'Daily maximum limit amount must be greater than or equal maximum transfer limit',
            'monthly_maximum_limit.gte'    => 'Monthly maximum limit amount must be greater than or equal daily maximum limit',
            'monthly_total_transaction.gte' => 'Monthly maximum count limit must be greater than or equal daily maximum count limit',
        ]);

        $setting = WireTransferSetting::first() ?? new WireTransferSetting();
        $setting->minimum_limit            = $request->minimum_limit;
        $setting->maximum_limit            = $request->maximum_limit;
        $setting->daily_maximum_limit      = $request->daily_maximum_limit;
        $setting->monthly_maximum_limit    = $request->monthly_maximum_limit;
        $setting->daily_total_transaction   = $request->daily_total_transaction;
        $setting->monthly_total_transaction = $request->monthly_total_transaction;
        $setting->fixed_charge              = $request->fixed_charge;
        $setting->percent_charge            = $request->percent_charge;
        $setting->instruction               = $request->instruction;
        $setting->save();

        $notify[] = ['success', 'Setting updated successfully'];
        return back()->withNotify($notify);
    }
}
