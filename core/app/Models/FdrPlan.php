<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class FdrPlan extends Model
{
    use GlobalStatus;

    public function verifications()
    {
        return $this->morphMany(OtpVerification::class, 'verifiable');
    }
}
