<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Dps extends Model
{
    use ApiQuery;

    protected $guarded = ['id'];

    protected $casts = [
        'withdrawn_at'          => 'datetime',
        'next_installment_date' => 'datetime',
        'due_notification_sent' => 'datetime',
    ];

    /* ========= Relations ========= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(DpsPlan::class, 'plan_id', 'id');
    }

    public function installments()
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    public function lateInstallments()
    {
        return $this->morphMany(Installment::class, 'installmentable')->whereNull('given_at')->whereDate('installment_date', '<', now()->format('Y-m-d'));
    }

    public function nextInstallment()
    {
        return $this->morphOne(Installment::class, 'installmentable')->whereNull('given_at');
    }

    /*========= Scopes =========*/
    public function scopeRunning($query)
    {
        return $query->where('dps.status', Status::DPS_RUNNING);
    }

    public function scopeMatured($query)
    {
        return $query->where('dps.status', Status::DPS_MATURED);
    }

    public function scopeClosed($query)
    {
        return $query->where('dps.status', Status::DPS_CLOSED);
    }

    public function scopeDue($query)
    {
        return $query->where('dps.status', Status::DPS_RUNNING)->whereHas('installments', function ($q) {
            $q->whereNull('given_at')->whereDate('installment_date', '<', now()->format('Y-m-d'));
        });
    }

    /* ========= Accessors ========= */
    public function statusBadge(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->status == Status::DPS_RUNNING) {
                return createBadge('success', 'Running');
            } elseif ($this->status == Status::DPS_MATURED) {
                return createBadge('warning', 'Matured');
            } else {
                return createBadge('dark', 'Closed');
            }
        });
    }

    // ========= Other Methods ========= //

    public function depositedAmount()
    {
        return $this->per_installment * $this->total_installment;
    }

    public function profitAmount()
    {
        return $this->depositedAmount() * $this->interest_rate / 100;
    }

    public function withdrawableAmount()
    {

        return $this->depositedAmount() + $this->profitAmount() - $this->delay_charge;
    }

    public function shortCodes()
    {
        return [
            'plan_name'              => $this->plan->name,
            'dps_number'             => $this->dps_number,
            'per_installment'        => $this->per_installment,
            'interest_rate'          => $this->interest_rate,
            'installment_interval'   => $this->installment_interval,
            'delay_value'            => $this->delay_value,
            'charge_per_installment' => $this->charge_per_installment,
            'delay_charge'           => $this->delay_charge,
            'given_installment'      => $this->given_installment,
            'total_installment'      => $this->total_installment,
            'total_deposited'        => $this->depositedAmount(),
            'withdrawable_amount'    => $this->withdrawableAmount(),
        ];
    }
}
