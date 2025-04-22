<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use GlobalStatus;

    public function assignStaff()
    {
        return $this->belongsToMany(BranchStaff::class, 'assign_branch_staff', 'branch_id', 'staff_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'branch_id')->where('status', Status::PAYMENT_SUCCESS);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'branch_id')->where('status', Status::PAYMENT_SUCCESS);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
