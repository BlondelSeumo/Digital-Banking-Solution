<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class OtherBank extends Model {
    use GlobalStatus;

    public function beneficiaryTypes() {
        return $this->morphMany(Beneficiary::class, 'beneficiary', 'beneficiary_type', 'beneficiary_id');
    }

    public function form() {
        return $this->belongsTo(Form::class);
    }

    // Accessors
    public function chargeText(): Attribute {
        return Attribute::make(get: function () {
            $charge = '';

            if ($this->percent_charge > 0) {
                $charge .= getAmount($this->percent_charge) . '%';
            }

            if ($this->percent_charge > 0 && $this->fixed_charge > 0) {
                $charge .= ' + ';
            }

            if ($this->fixed_charge > 0) {
                $charge .= gs('cur_sym') . showAmount($this->fixed_charge, currencyFormat:false);
            }

            return $charge;
        });
    }
}
