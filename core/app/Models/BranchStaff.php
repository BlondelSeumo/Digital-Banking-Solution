<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BranchStaff extends Authenticatable
{
    use GlobalStatus;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['branch_id'];

    public function assignBranch()
    {
        return $this->belongsToMany(Branch::class, 'assign_branch_staff', 'staff_id', 'branch_id');
    }

    public function branch()
    {
        return $this->assignBranch()->first();
    }

    protected function branchId(): Attribute
    {
        return new Attribute(
            get: fn () => $this->assignBranch->pluck('id')->toArray()
        );
    }

    public function statusBadge(): Attribute
    {
        return Attribute::make(get: function () {
            $badge = '';
            if ($this->status == Status::STAFF_ACTIVE) {
                $badge = createBadge('success', 'Active');
            } else {
                $badge = createBadge('warning', 'Banned');
            }
            return $badge;
        });
    }

    public function designationText(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->roll == Status::ROLE_ACCOUNT_OFFICER ? 'Account Officer' : 'Branch Manager';
        });
    }
}
