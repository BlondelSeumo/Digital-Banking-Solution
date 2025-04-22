@extends('pdf.layouts.master')

@section('main-content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('Loan No.') | @lang('Plan')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Installment Amount')</th>
                <th>@lang('Installment')</th>
                <th>@lang('Next Installment')</th>
                <th>@lang('Paid')</th>
                <th>@lang('Status')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>
                        <span>{{ __($loan->loan_number) }}</span>
                        <br>
                        <small class="text--base">{{ __($loan->plan->name) }}</small>
                    </td>

                    <td>
                        <span>{{showAmount($loan->amount) }}</span>
                        <br>
                        <small class="text--base">
                            {{showAmount($loan->payable_amount) }} @lang('Need to pay')
                        </small>
                    </td>

                    <td>
                        <span>{{showAmount($loan->per_installment) }}</span>
                        <br>
                        <small class="text--base">
                            @lang('In Every') {{ $loan->installment_interval }} {{__(Str::plural('Day', $loan->installment_interval))}}
                        </small>
                    </td>

                    <td>
                        <span> @lang('Total') : {{ __($loan->total_installment) }}</span>
                        <br>
                        <small class="text--base">
                            @lang('Given') : {{ __($loan->given_installment) }}
                        </small>
                    </td>

                    <td>
                        @if ($loan->nextInstallment)
                            {{ showDateTime($loan->nextInstallment->installment_date, 'd M, Y') }}
                        @endif
                    </td>

                    <td>
                        <span>{{showAmount($loan->paid_amount) }}</span>
                        <br>
                        <span class="text--warning">
                            @php $remainingAmount = $loan->payableAmount - $loan->paid_amount;  @endphp
                            <small> {{showAmount($remainingAmount) }} @lang('Remaining')</small>
                        </span>
                    </td>

                    <td>
                        @php echo $loan->statusBadge; @endphp
                        @if ($loan->status == 3)
                            <span class="admin-feedback" data-feedback="{{ __($loan->admin_feedback) }}">
                                <i class="la la-info-circle"></i>
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
