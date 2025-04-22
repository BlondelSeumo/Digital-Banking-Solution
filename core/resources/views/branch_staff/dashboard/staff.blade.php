@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xl-4 col-md-6 col-12">
            <x-widget style="2" bg="white" color="danger" icon="la la-map-marker" title="{{ @$branch->address }}" value="{{ @$branch->name }} Branch"  />
        </div>

        <div class="col-xl-8 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Find an Account')</h5>
                    <form action="{{ route('staff.account.find') }}" method="GET">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control form-control-lg" name="account_number" type="text" value="{{ old('account_number') }}" placeholder="@lang('Account Number / Username')" required>
                                <button class="input-group-text btn btn--primary" type="submit"><i class="la la-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-4">
        <div class="col-xxl-6 col-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title">@lang('Latest Deposit')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('TRX No.')</th>
                                    <th>@lang('Account Number')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td>{{ __($deposit->trx) }}</td>
                                        <td>
                                            <a href="{{ route('staff.account.detail', encrypt($deposit->user->account_number)) }}">
                                                {{ @$deposit->user->account_number }}
                                            </a>
                                        </td>
                                        <td>{{ showDateTime($deposit->created_at, 'd M Y, h:i A') }}</td>
                                        <td>
                                            {{ showAmount($deposit->amount + $deposit->charge) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title">@lang('Latest Withdrawals')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('TRX No.')</th>
                                    <th>@lang('Account Number')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                    <tr>
                                        <td>{{ __($withdrawal->trx) }}</td>
                                        <td>
                                            <a href="{{ route('staff.account.detail', encrypt($withdrawal->user->account_number)) }}">
                                                {{ @$withdrawal->user->account_number }}
                                            </a>
                                        </td>
                                        <td>{{ showDateTime($withdrawal->created_at, 'd M Y, h:i A') }}</td>
                                        <td>
                                            {{ showAmount($withdrawal->amount) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
