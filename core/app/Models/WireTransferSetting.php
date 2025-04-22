<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WireTransferSetting extends Model {


    public function verifications() {
        return $this->morphMany(OtpVerification::class, 'verifiable');
    }

    public function chargeText() {
        $charge = '';

        if ($this->percent_charge > 0) $charge .= getAmount($this->percent_charge) . '%';

        if ($this->percent_charge > 0 && $this->fixed_charge > 0) $charge .= ' + ';

        if ($this->fixed_charge > 0) $charge .= gs('cur_sym') . showAmount($this->fixed_charge, currencyFormat:false);

        return $charge;
    }
}
