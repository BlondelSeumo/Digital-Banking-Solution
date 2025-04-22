<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use GlobalStatus;
    protected $casts = [
        'logo_urls'                        => 'array',
        'fixed_amounts'                    => 'array',
        'fixed_amounts_descriptions'       => 'object',
        'local_fixed_amounts'              => 'array',
        'local_fixed_amounts_descriptions' => 'object',
        'suggested_amounts'                => 'array',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function verifications()
    {
        return $this->morphMany(OtpVerification::class, 'verifiable');
    }
}
