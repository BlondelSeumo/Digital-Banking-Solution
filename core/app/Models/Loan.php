<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Google\Service\ShoppingContent\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use ApiQuery;

    protected $guarded = ['id'];

    protected $casts = [
        'due_notification_sent' => 'datetime',
        'approved_at'           => 'datetime',
        'application_form'      => 'object',
    ];

    /* ========= Relations ========= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(LoanPlan::class, 'plan_id', 'id');
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

    public function interestRate() {
        return ($this->payable_amount - $this->amount) / $this->amount * 100;
    }

    /*========= Scopes =========*/
    public function scopePending($query)
    {
        return $query->where('loans.status', Status::LOAN_PENDING);
    }

    public function scopeRunning($query)
    {
        return $query->where('loans.status', Status::LOAN_RUNNING);
    }

    public function scopePaid($query)
    {
        return $query->where('loans.status', Status::LOAN_PAID);
    }

    public function scopeRejected($query)
    {
        return $query->where('loans.status', Status::LOAN_REJECTED);
    }

    public function scopeApproved($query)
    {
        return $query->where('loans.status', '!=', Status::LOAN_REJECTED);
    }

    public function scopeDue($query)
    {
        return $query->where('loans.status', Status::LOAN_RUNNING)->whereHas('installments', function ($q) {
            $q->whereNull('given_at')->whereDate('installment_date', '<', now()->format('Y-m-d'));
        });
    }

    /* ========= Accessors ========= */
    public function statusBadge(): Attribute
    {
        return Attribute::make(get: function () {
            $badge = '';
            if ($this->status == Status::LOAN_PENDING) {
                $badge = createBadge('dark', 'Pending');
            } elseif ($this->status == Status::LOAN_RUNNING) {
                $badge = createBadge('warning', 'Running');
            } elseif ($this->status == Status::LOAN_PAID) {
                $badge = createBadge('success', 'Paid');
            } else {
                $badge = createBadge('danger', 'Rejected');
            }
            return $badge;
        });
    }

    public function payableAmount(): Attribute
    {
        return Attribute::make(get: fn () => $this->per_installment * $this->total_installment);
    }

    public function paidAmount(): Attribute
    {
        return Attribute::make(get: fn () => $this->per_installment * $this->given_installment);
    }

    /* ========= Other Methods ========= */

    public function shortCodes()
    {
        return [
            "plan_name"              => $this->plan->name,
            "loan_number"            => $this->loan_number,
            "amount"                 => $this->amount,
            "per_installment"        => $this->per_installment,
            "payable_amount"         => $this->payable_amount,
            "installment_interval"   => $this->installment_interval,
            "delay_value"            => $this->delay_value,
            "charge_per_installment" => $this->charge_per_installment,
            "delay_charge"           => $this->delay_charge,
            "given_installment"      => $this->given_installment,
            "total_installment"      => $this->total_installment,
            "reason_of_rejection"    => $this->admin_feedback,
        ];
    }
}
