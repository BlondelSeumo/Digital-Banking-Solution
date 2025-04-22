<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use ApiQuery;
    
    protected $casts = [
        'details' => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifications()
    {
        return $this->morphMany(OtpVerification::class, 'verifiable');
    }

    public function beneficiaryOf()
    {
        return $this->morphTo('beneficiaryOf', 'beneficiary_type', 'beneficiary_id');
    }

    public function scopeOwnBank($query)
    {
        $query->where('beneficiary_type', User::class);
    }

    public function scopeOtherBank($query)
    {
        $query->where('beneficiary_type', OtherBank::class);
    }

    public function scopeCountry($query){
        $query->where('beneficiary_type', Country::class);
    }
}
