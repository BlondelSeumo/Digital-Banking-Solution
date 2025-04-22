<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BalanceTransfer extends Model
{
    use ApiQuery;

    protected $guarded = ['id'];

    protected $casts = [
        'wire_transfer_data' => 'object',
        'amount' => 'double',
        'charge' => 'double'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('balance_transfers.status', Status::TRANSFER_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('balance_transfers.status', Status::TRANSFER_COMPLETED);
    }

    public function scopeRejected($query)
    {
        return $query->where('balance_transfers.status', Status::TRANSFER_REJECTED);
    }

    public function scopeNotRejected($query)
    {
        return $query->where('balance_transfers.status', '!=', Status::TRANSFER_REJECTED);
    }

    public function scopeWireTransfer($query)
    {
        return $query->where('balance_transfers.beneficiary_id', 0);
    }

    public function scopeOwnBank($query)
    {
        return $query->whereHas('beneficiary', function ($q) {
            return $q->where('beneficiary_type', User::class);
        });
    }

    public function scopeOtherBank($query)
    {
        return $query->whereHas('beneficiary', function ($q) {
            return $q->where('beneficiary_type', OtherBank::class);
        });
    }

    /* ========= Accessors ========= */
    public function statusBadge(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->status == Status::TRANSFER_PENDING) {
                return createBadge('warning', 'Pending');
            } elseif ($this->status == Status::TRANSFER_COMPLETED) {
                return createBadge('success', 'Completed');
            } else {
                return createBadge('danger', 'Rejected');
            }
        });
    }
    public function finalAmount(): Attribute
    {
        return Attribute::make(get: fn () => $this->amount + $this->charge);
    }

    // Other Methods

    public function wireTransferAccountNumber()
    {
        $number = collect($this->wire_transfer_data)->where('name', 'Account Number')->first();
        return $number->value ?? '';
    }

    public function wireTransferAccountName()
    {
        $name = collect($this->wire_transfer_data)->where('name', 'Account Name')->first();
        return $name->value ?? '';
    }

}
