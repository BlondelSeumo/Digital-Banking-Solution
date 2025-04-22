<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Lib\Reloadly;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Dps;
use App\Models\Fdr;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Operator;
use App\Models\Transaction;
use Carbon\Carbon;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }


    public function dps()
    {

        try {
            $installments = Installment::where('installmentable_type', Dps::class)
                ->whereDate('installment_date', '<=', today())
                ->whereNull('given_at')
                ->whereHas('installmentable', function ($q) {
                    return $q->where('status', Status::DPS_RUNNING);
                })
                ->with('installmentable.user')
                ->get();


            foreach ($installments as $installment) {
                $dps    = $installment->installmentable;
                $amount = $dps->per_installment;
                $user   = $dps->user;

                $shortCodes = $dps->shortCodes();

                if ($user->balance < $amount) {
                    $lastNotification = $dps->due_notification_sent ?? now()->subHours(10);

                    if ($lastNotification->diffInHours(now()) >= 10) {
                        // Notify user after 10 hours from the previous one.
                        $shortCodes['installment_date'] = showDateTime($installment->installment_date, 'd M, Y');
                        notify($user, 'DPS_INSTALLMENT_DUE', $shortCodes);
                        $dps->due_notification_sent = now();
                        $dps->save();
                    }
                } else {

                    $delayedDays = $installment->installment_date->diffInDays(today());
                    $charge      = 0;

                    if ($delayedDays >= $dps->delay_value) {
                        $charge = $dps->charge_per_installment * $delayedDays;
                        $dps->delay_charge += $charge;
                    }

                    $dps->given_installment += 1;

                    if ($dps->given_installment == $dps->total_installment) {
                        $dps->status = Status::DPS_MATURED;
                        notify($user, 'DPS_MATURED', $shortCodes);
                    }

                    $dps->save();

                    $user->balance -= $amount;
                    $user->save();

                    $installment->given_at     = now();
                    $installment->delay_charge = $charge;
                    $installment->save();

                    $transaction               = new Transaction();
                    $transaction->user_id      = $user->id;
                    $transaction->amount       = $amount;
                    $transaction->post_balance = $user->balance;
                    $transaction->charge       = 0;
                    $transaction->trx_type     = '-';
                    $transaction->details      = 'DPS installment paid';
                    $transaction->remark       = 'dps_installment';
                    $transaction->trx          = $dps->dps_number;
                    $transaction->save();
                }
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function loan()
    {
        try {
            $installments = Installment::where('installmentable_type', Loan::class)
                ->whereDate('installment_date', '<=', today())
                ->whereNull('given_at')
                ->whereHas('installmentable', function ($q) {
                    return $q->where('status', Status::LOAN_RUNNING);
                })
                ->with('installmentable')
                ->get();

            foreach ($installments as $installment) {
                $loan   = $installment->installmentable;
                $amount = $loan->per_installment;
                $user   = $loan->user;

                $shortCodes = $loan->shortCodes();

                if ($user->balance < $amount) {
                    $lastNotification = $loan->due_notification_sent ?? now()->subHours(10);

                    if ($lastNotification->diffInHours(now()) >= 10) {
                        // Notify user after 10 hours from the previous one.
                        $shortCodes['installment_date'] = showDateTime($installment->installment_date, 'd M, Y');
                        notify($user, 'LOAN_INSTALLMENT_DUE', $shortCodes);
                        $loan->due_notification_sent = now();
                        $loan->save();
                    }
                } else {
                    $delayedDays = $installment->installment_date->diffInDays(today());
                    $charge      = 0;

                    if ($delayedDays >= $loan->delay_value) {
                        $charge = $loan->charge_per_installment * $delayedDays;
                        $loan->delay_charge += $charge;
                    }

                    $loan->given_installment += 1;

                    if ($loan->given_installment == $loan->total_installment) {
                        $loan->status = Status::LOAN_PAID;
                        notify($user, 'LOAN_PAID', $shortCodes);
                    }

                    $loan->save();

                    $amount += $charge;

                    $user->balance -= $amount;
                    $user->save();

                    $installment->given_at     = now();
                    $installment->delay_charge = $charge;
                    $installment->save();

                    $transaction               = new Transaction();
                    $transaction->user_id      = $user->id;
                    $transaction->amount       = $amount;
                    $transaction->post_balance = $user->balance;
                    $transaction->charge       = $charge;
                    $transaction->trx_type     = '-';
                    $transaction->details      = 'Loan installment paid';
                    $transaction->remark       = 'loan_installment';
                    $transaction->trx          = $loan->loan_number;
                    $transaction->save();
                }
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function fdr()
    {
        try {
            $allFdr = Fdr::running()->whereDate('next_installment_date', '<=', now()->format('y-m-d'))->with('user:id,username,balance')->get();

            foreach ($allFdr as $fdr) {
                self::payFdrInstallment($fdr);
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public static function payFdrInstallment($fdr)
    {
        $amount                     = $fdr->per_installment;
        $user                       = $fdr->user;
        $fdr->next_installment_date = $fdr->next_installment_date->addDays($fdr->installment_interval);
        $fdr->profit += $amount;
        $fdr->save();

        $user->balance += $amount;
        $user->save();

        $installment                   = new Installment();
        $installment->installment_date = $fdr->next_installment_date->subDays($fdr->installment_interval);
        $installment->given_at         = now();
        $fdr->installments()->save($installment);

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'FDR installment received';
        $transaction->remark       = 'fdr_installment';
        $transaction->trx          = $fdr->fdr_number;
        $transaction->save();
    }

    public function updateOperators()
    {
        try {
            $reloadly = new Reloadly();
            Operator::active()->chunk(20, function ($operators) use ($reloadly) {
                foreach ($operators as $operator) {
                    $item = $reloadly->getOperatorsByID($operator->unique_id);
                    if($item) {
                        $operator->name                                  = $item->name;
                        $operator->bundle                                = $item->bundle ? 1 : 0;
                        $operator->data                                  = $item->data ? 1 : 0;
                        $operator->pin                                   = $item->pin ? 1 : 0;
                        $operator->denomination_type                     = $item->denominationType;
                        $operator->destination_currency_code             = $item->destinationCurrencyCode;
                        $operator->destination_currency_symbol           = $item->destinationCurrencySymbol;
                        $operator->most_popular_amount                   = $item->mostPopularAmount;
                        $operator->min_amount                            = $item->minAmount;
                        $operator->max_amount                            = $item->maxAmount;
                        $operator->logo_urls                             = $item->logoUrls;
                        $operator->fixed_amounts                         = $item->fixedAmounts;
                        $operator->fixed_amounts_descriptions            = $item->fixedAmountsDescriptions;
                        $operator->local_fixed_amounts                   = $item->localFixedAmounts;
                        $operator->local_fixed_amounts_descriptions      = $item->localFixedAmountsDescriptions;
                        $operator->suggested_amounts                     = $item->suggestedAmounts;
                        $operator->save();
                    }
                }
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
