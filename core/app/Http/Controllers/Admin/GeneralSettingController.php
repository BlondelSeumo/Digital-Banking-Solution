<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ApiConfiguration;
use App\Models\TableConfiguration;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller {
    public function systemSetting() {
        $pageTitle = 'System Settings';
        $settings = json_decode(file_get_contents(resource_path('views/admin/setting/settings.json')));
        return view('admin.setting.system', compact('pageTitle', 'settings'));
    }
    public function general() {
        $pageTitle = 'General Settings';
        $timezones = timezone_identifiers_list();
        $currentTimezone = array_search(config('app.timezone'), $timezones);
        return view('admin.setting.general', compact('pageTitle', 'timezones', 'currentTimezone'));
    }

    public function generalUpdate(Request $request) {
        $request->validate([
            'site_name'       => 'required|string|max:40',
            'cur_text'        => 'required|string|max:40',
            'cur_sym'         => 'required|string|max:40',
            'base_color'      => 'nullable|regex:/^[a-f0-9]{6}$/i',
            'secondary_color' => 'nullable|regex:/^[a-f0-9]{6}$/i',
            'timezone'        => 'required|integer',
            'currency_format' => 'required|in:1,2,3',
            'paginate_number' => 'required|integer',
            'account_no_prefix'        => 'nullable|string|max:40',
            'account_no_length'        => 'nullable|integer|min:12|max:100',
            'otp_time'                 => 'required|integer|gt:0',
            'minimum_transfer_limit'   => 'nullable|numeric|gte:0',
            'daily_transfer_limit'     => 'nullable|numeric|gte:minimum_transfer_limit',
            'monthly_transfer_limit'   => 'nullable|numeric|gte:daily_transfer_limit',
            'fixed_transfer_charge'    => 'nullable|numeric|gte:0',
            'percent_transfer_charge'  => 'nullable|numeric|gte:0',
            'idle_time_threshold'      => 'nullable|numeric|min:60',
        ]);

        $timezones = timezone_identifiers_list();
        $timezone = @$timezones[$request->timezone] ?? 'UTC';

        $general = gs();
        $general->site_name = $request->site_name;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->paginate_number = $request->paginate_number;
        $general->base_color = str_replace('#', '', $request->base_color);
        $general->secondary_color = str_replace('#', '', $request->secondary_color);
        $general->currency_format = $request->currency_format;

        $general->account_no_prefix            = $request->account_no_prefix;
        $general->account_no_length            = $request->account_no_length;
        $general->otp_time                     = $request->otp_time;
        $general->minimum_transfer_limit       = $request->minimum_transfer_limit;
        $general->daily_transfer_limit         = $request->daily_transfer_limit;
        $general->monthly_transfer_limit       = $request->monthly_transfer_limit;
        $general->fixed_transfer_charge        = $request->fixed_transfer_charge;
        $general->percent_transfer_charge      = $request->percent_transfer_charge;
        $general->idle_time_threshold          = $request->idle_time_threshold;

        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = "' . $timezone . '" ?>';
        file_put_contents($timezoneFile, $content);
        $notify[] = ['success', 'General setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function systemConfiguration() {
        $pageTitle = 'System Configuration';
        $modules   = gs()->modules;
        return view('admin.setting.configuration', compact('pageTitle', 'modules'));
    }

    public function systemConfigurationSubmit(Request $request) {
        $general = gs();
        $general->kv = $request->kv ? Status::ENABLE : Status::DISABLE;
        $general->ev = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->pn = $request->pn ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->registration = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->agree = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->multi_language = $request->multi_language ? Status::ENABLE : Status::DISABLE;
        $general->in_app_payment = $request->in_app_payment ? Status::ENABLE : Status::DISABLE;
        $general->detect_activity = $request->detect_activity ? Status::ENABLE : Status::DISABLE;

        //module
        $modules['deposit']            = isset($request->module['deposit']) ? Status::YES : Status::NO;
        $modules['withdraw']           = isset($request->module['withdraw']) ? Status::YES : Status::NO;
        $modules['dps']                = isset($request->module['dps']) ? Status::YES : Status::NO;
        $modules['fdr']                = isset($request->module['fdr']) ? Status::YES : Status::NO;
        $modules['loan']               = isset($request->module['loan']) ? Status::YES : Status::NO;
        $modules['own_bank']           = isset($request->module['own_bank']) ? Status::YES : Status::NO;
        $modules['other_bank']         = isset($request->module['other_bank']) ? Status::YES : Status::NO;
        $modules['otp_email']          = isset($request->module['otp_email']) ? Status::YES : Status::NO;
        $modules['otp_sms']            = isset($request->module['otp_sms']) ? Status::YES : Status::NO;
        $modules['branch_create_user'] = isset($request->module['branch_create_user']) ? Status::YES : Status::NO;
        $modules['wire_transfer']      = isset($request->module['wire_transfer']) ? Status::YES : Status::NO;
        $modules['referral_system']    = isset($request->module['referral_system']) ? Status::YES : Status::NO;
        $modules['airtime']            = isset($request->module['airtime']) ? Status::YES : Status::NO;

        $general->modules = $modules;
        $general->save();

        $notify[] = ['success', 'System configuration updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon() {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request) {
        $request->validate([
            'logo' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'logo_dark' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon' => ['image', new FileTypeValidate(['png'])],
        ]);
        $path = getFilePath('logoIcon');
        if ($request->hasFile('logo')) {
            try {
                fileUploader($request->logo, $path, filename: 'logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('logo_dark')) {
            try {
                fileUploader($request->logo_dark, $path, filename: 'logo_dark.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                fileUploader($request->favicon, $path, filename: 'favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon updated successfully'];
        return back()->withNotify($notify);
    }

    public function socialiteCredentials() {
        $pageTitle = 'Social Login Credentials';
        return view('admin.setting.social_credential', compact('pageTitle'));
    }

    public function updateSocialiteCredentialStatus($key) {
        $general = gs();
        $credentials = $general->socialite_credentials;
        try {
            $credentials->$key->status = $credentials->$key->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        } catch (\Throwable $th) {
            abort(404);
        }

        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function updateSocialiteCredential(Request $request, $key) {
        $general = gs();
        $credentials = $general->socialite_credentials;
        try {
            @$credentials->$key->client_id = $request->client_id;
            @$credentials->$key->client_secret = $request->client_secret;
        } catch (\Throwable $th) {
            abort(404);
        }
        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', ucfirst($key) . ' credential updated successfully'];
        return back()->withNotify($notify);
    }

    public function inAppPurchase() {
        $pageTitle = 'In App Purchase Configuration - Google Play Store';
        $data      = null;
        $fileExists = file_exists(getFilePath('appPurchase') . '/google_pay.json');
        return view('admin.setting.in_app_purchase.google', compact('pageTitle', 'data', 'fileExists'));
    }

    public function inAppPurchaseConfigure(Request $request) {
        $request->validate([
            'file' => ['required', new FileTypeValidate(['json'])],
        ]);

        try {
            fileUploader($request->file, getFilePath('appPurchase'), filename: 'google_pay.json');
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload your file'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Configuration file uploaded successfully'];
        return back()->withNotify($notify);
    }

    public function inAppPurchaseFileDownload() {
        $filePath = getFilePath('appPurchase') . '/google_pay.json';
        if (!file_exists(getFilePath('appPurchase') . '/google_pay.json')) {
            $notify[] = ['success', "File not found"];
            return back()->withNotify($notify);
        }
        return response()->download($filePath);
    }

    public function apiConfiguration() {
        $pageTitle = 'Api Configuration';
        $reloadly = ApiConfiguration::where('provider', 'reloadly')->firstOrFail();
        return view('admin.setting.api_configuration', compact('pageTitle', 'reloadly'));
    }

    public function saveAirtimeApiCredentials(Request $request) {
        $request->validate([
            'credentials.client_id'     => 'required|string',
            'credentials.client_secret' => 'required|string',
            'test_mode'                 => 'nullable|in:on'
        ], [
            'credentials.client_id.required' => 'The client id field is required',
            'credentials.client_secret.required' => 'The client secret field is required'
        ]);

        $apiConfig = ApiConfiguration::where('provider', 'reloadly')->firstOrFail();
        $apiConfig->credentials = $request->credentials;
        $apiConfig->test_mode = $request->test_mode ? Status::ENABLE : Status::DISABLE;
        $apiConfig->save();

        $notify[] = ['success', 'API credentials updated successfully'];
        return back()->withNotify($notify);
    }

    public function configureTable(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'visible_columns' => 'required|array|min:1'
        ]);

        $adminId = auth()->guard('admin')->id();

        $tableConfiguration = TableConfiguration::where('admin_id', $adminId)->where('table_name', $request->name)->first() ?? new TableConfiguration();

        $tableConfiguration->admin_id = $adminId;
        $tableConfiguration->table_name = $request->name;
        $tableConfiguration->visible_columns = $request->visible_columns;
        $tableConfiguration->save();

        $notify[] = ['success', 'Configuration saved successfully'];
        return back()->withNotify($notify);
    }
}
