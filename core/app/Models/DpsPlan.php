<?php

namespace App\Models;

use App\Traits\ApiQuery;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class DpsPlan extends Model {
    use GlobalStatus, ApiQuery;

    public function verifications() {
        return $this->morphMany(OtpVerification::class, 'verifiable');
    }

    public function delayCharge(): Attribute {
        return Attribute::make(get:fn() => $this->fixed_charge + ($this->per_installment * $this->percent_charge / 100));
    }
}
