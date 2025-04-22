<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function branchStaff()
    {
        return $this->belongsTo(BranchStaff::class, 'branch_staff_id');
    }

    public function scopeReferralCommission($query)
    {
        return $query->where('remark', 'referral_commission');
    }

    public function scopePlus($query)
    {
        return $query->where('trx_type', '+');
    }

    public function scopeMinus($query)
    {
        return $query->where('trx_type', '-');
    }

    public function scopeSumAmount($query)
    {
        return $query->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date");
    }

    public function scopeLastDays($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
