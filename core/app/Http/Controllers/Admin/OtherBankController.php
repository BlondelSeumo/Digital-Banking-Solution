<?php

namespace App\Http\Controllers\Admin;

use App\Models\OtherBank;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Form;

class OtherBankController extends Controller {
    public function index() {
        $pageTitle = 'Other Banks';
        $banks     = OtherBank::searchable(['name'])
        ->filterable()
        ->orderable()
        ->paginate(getPaginate());
        return view('admin.other_banks.index', compact('pageTitle', 'banks'));
    }

    public function create() {
        $pageTitle = 'Add New Bank';
        $form      = new Form();
        $form->mergeDefaultTransferFields();
        return view('admin.other_banks.form', compact('pageTitle', 'form'));
    }

    public function edit($id) {
        $bank      = OtherBank::findOrFail($id);
        $pageTitle = "Edit Bank";
        $form      = $bank->form;
        $form->mergeDefaultTransferFields();
        return view('admin.other_banks.form', compact('pageTitle', 'bank', 'form'));
    }

    public function store(Request $request, $id = 0) {
        $this->validation($request);
        $formProcessor       = new FormProcessor();

        if ($id) {
            $bank         = OtherBank::findOrFail($id);
            $form         = $formProcessor->generate('other_bank', true, 'id', $bank->form_id);
            $message      = "New bank updated successfully";
        } else {
            $bank         = new OtherBank();
            $form         = $formProcessor->generate('other_bank');
            $message      = "New bank added successfully";
        }

        $bank->name                      = $request->name;
        $bank->minimum_limit             = $request->minimum_amount;
        $bank->maximum_limit             = $request->maximum_amount;
        $bank->daily_maximum_limit       = $request->daily_maximum_amount;
        $bank->monthly_maximum_limit     = $request->monthly_maximum_amount;
        $bank->daily_total_transaction   = $request->daily_transaction_count;
        $bank->monthly_total_transaction = $request->monthly_transaction_count;
        $bank->fixed_charge              = $request->fixed_charge;
        $bank->percent_charge            = $request->percent_charge;
        $bank->processing_time           = $request->processing_time;
        $bank->instruction               = $request->instruction;
        $bank->form_id                   = $form->id;
        $bank->save();
        $notify[] = ['success', $message];
        return redirect()->back()->withNotify($notify);
    }

    public function changeStatus($id) {
        return OtherBank::changeStatus($id);
    }

    protected function validation($request) {
        $rules = [
            'name'                      => 'required|string|max:40',
            'processing_time'           => 'required|string|max:255',
            'minimum_amount'            => 'required|numeric|gt:0',
            'maximum_amount'            => 'required|numeric|gt:minimum_amount',
            'daily_maximum_amount'      => 'required|numeric|gte:maximum_amount',
            'monthly_maximum_amount'    => 'required|numeric|gte:maximum_amount',
            'daily_transaction_count'   => 'required|integer|gt:0',
            'monthly_transaction_count' => 'required|integer|gt:0',
            'fixed_charge'              => 'required|numeric|gte:0',
            'percent_charge'            => 'required|numeric|gte:0',
            'instruction'               => 'nullable|max:64000',
            'field_name.*'              => 'sometimes|required',
            'type.*'                    => 'sometimes|required|in:text,textarea,file',
            'validation.*'              => 'sometimes|required|in:required,nullable',
        ];

        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation          = array_merge($rules, $generatorValidation['rules']);
        $request->validate($validation, array_merge(@$generatorValidation['messages'] ?? [], [
            'field_name.*.required' => 'All field is required'
        ]));
    }
}
