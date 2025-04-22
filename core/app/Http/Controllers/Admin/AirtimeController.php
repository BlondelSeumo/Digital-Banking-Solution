<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\Reloadly;
use App\Models\Country;
use App\Models\Operator;
use App\Models\OperatorGroup;
use Illuminate\Http\Request;

class AirtimeController extends Controller {

    public function countries() {
        $pageTitle = 'Airtime Countries';
        $countries = Country::searchable(['name', 'iso_name', 'continent', 'currency_code', 'currency_symbol', 'calling_codes'])
            ->filterable()
            ->orderable()
            ->withCount('operators')
            ->dynamicPaginate();

        if (session()->has('countries')) session()->forget('countries');

        return view('admin.airtime.countries', compact('pageTitle', 'countries'));
    }

    public function fetchCountries() {
        $pageTitle    = 'Reloadly Supported Countries';
        $countries    = Country::get();

        $reloadly     = new Reloadly();
        $apiCountries = $reloadly->getCountries();

        session()->put('countries', $apiCountries);

        return view('admin.airtime.fetch_countries', compact('pageTitle', 'apiCountries', 'countries'));
    }

    public function saveCountries(Request $request) {
        $request->validate([
            'countries' => 'required|array|min:1',
        ]);

        $countryArray     = [];
        $requestCountries = collect(session('countries'))->whereIn('isoName', $request->countries);
        session()->forget('countries');

        foreach ($requestCountries as $item) {
            $country = Country::where('iso_name', @$item->isoName)->first();

            if ($country) continue;

            $countryArray[] = [
                'name'            => $item->name,
                'iso_name'        => $item->isoName,
                'continent'       => $item->continent,
                'currency_code'   => $item->currencyCode,
                'currency_name'   => $item->currencyName,
                'currency_symbol' => $item->currencySymbol,
                'flag_url'        => $item->flag,
                'calling_codes'   => json_encode($item->callingCodes),
            ];
        }

        Country::insert($countryArray);

        $notify[] = ['success', 'Country added successfully'];
        return to_route('admin.airtime.countries')->withNotify($notify);
    }

    public function updateCountryStatus($id) {
        return Country::changeStatus($id);
    }

    public function operators($iso = null) {

        if ($iso) {
            $country = Country::where('iso_name', $iso)->firstOrFail();
        }
        $operatorGroups = [];

        if (request()->has('filter.country')) {
            $country = Country::where('name', request()->filter['country'])->firstOrFail();

            $iso = $country->iso_name;
        }

        if($iso) {
            $operatorGroups = $country->operatorGroups()->pluck('name')->toArray();
        }

        $pageTitle = 'Airtime Operators';

        $operators = Operator::searchable(['name'])
            ->selectRaw('operators.*, countries.name as country, operator_groups.name as group_name')
            ->leftJoin('countries', 'operators.country_id', '=', 'countries.id')
            ->leftJoin('operator_groups', 'operators.operator_group_id', '=', 'operator_groups.id')
            ->filterable();

        if (request()->order_by_column) {
            $operators->orderable();
        } else {
            $operators->orderBy('name');
        }

        if ($iso) {
            $operators = $operators->where('operators.country_id', $country->id);
        }

        $operators = $operators->dynamicPaginate();

        if (session()->has('operators')) {
            session()->forget('operators');
        }

        return view('admin.airtime.operators', compact('pageTitle', 'operators', 'iso', 'operatorGroups'));
    }

    public function fetchOperatorsByISO($iso) {
        $country                    = Country::where('iso_name', $iso)->with('operators')->firstOrFail();
        $pageTitle                  = 'Reloadly Supported ' . $country->iso_name . ' Operators';
        $reloadly                   = new Reloadly();
        $reloadlySupportedOperators = $reloadly->getOperatorsByISO($iso);

        session()->put('operators', $reloadlySupportedOperators);

        return view('admin.airtime.fetch_operators', compact('pageTitle', 'country', 'reloadlySupportedOperators'));
    }

    public function saveOperators(Request $request, $iso) {

        $request->validate([
            'operators' => 'required|array|min:1',
        ]);

        $country          = Country::where('iso_name', $iso)->firstOrFail();
        $requestOperators = collect(session('operators'))->whereIn('operatorId', $request->operators);
        session()->forget('operators');

        foreach ($requestOperators as $item) {
            $operator = new Operator();
            $operator->country_id                           = $country->id;
            $operator->unique_id                            = $item->operatorId;
            $operator->name                                 = $item->name;
            $operator->bundle                               = $item->bundle ? 1 : 0;
            $operator->data                                 = $item->data ? 1 : 0;
            $operator->pin                                  = $item->pin ? 1 : 0;
            $operator->denomination_type                    = $item->denominationType;
            $operator->destination_currency_code            = $item->destinationCurrencyCode;
            $operator->destination_currency_symbol          = $item->destinationCurrencySymbol;
            $operator->most_popular_amount                  = $item->mostPopularAmount;
            $operator->min_amount                           = $item->minAmount;
            $operator->max_amount                           = $item->maxAmount;
            $operator->logo_urls                            = $item->logoUrls;
            $operator->fixed_amounts                        = $item->fixedAmounts;
            $operator->fixed_amounts_descriptions           = $item->fixedAmountsDescriptions;
            $operator->local_fixed_amounts                  = $item->localFixedAmounts;
            $operator->local_fixed_amounts_descriptions     = $item->localFixedAmountsDescriptions;
            $operator->suggested_amounts                    = $item->suggestedAmounts;

            $operator->operator_group_id = $this->getGroupId($operator, $country);
            $operator->save();
        }


        $notify[] = ['success', 'Operators added successfully'];
        return to_route('admin.airtime.operators', $country->iso_name)->withNotify($notify);
    }

    public function updateOperatorStatus($id) {
        return Operator::changeStatus($id);
    }

    private function getGroupId($operator, $country) {
        $groupName = $operator->name;

        $pos = stripos($groupName, 'RTR');

        if ($pos) {
            $groupName = trim(substr_replace($groupName, '', $pos));
        }


        $pos = stripos($groupName, 'PIN');

        if ($pos) {
            $groupName = trim(substr_replace($groupName, '', $pos));
        }

        $pos = stripos($groupName, ' ' . $country->name);

        if ($pos) {
            $groupName = trim(substr_replace($groupName, '', $pos));
        }

        $pos = stripos($groupName, ' ' . $country->iso_name);

        if ($pos) {
            $groupName = trim(substr_replace($groupName, '', $pos));
        }


        if($groupName) {
            $group = OperatorGroup::where('name', $groupName)->where('country_id', $country->id)->first();
            if(!$group){
                $group = new OperatorGroup();
                $group->country_id = $country->id;
                $group->name = $groupName;
                $group->save();
            }
            return $group->id;
        }

        return 0;
    }
}
