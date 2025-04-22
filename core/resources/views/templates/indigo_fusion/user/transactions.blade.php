@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="show-filter mb-3 text-end">
                <button class="btn btn--base showFilterBtn btn-sm" type="button"><i class="las la-filter"></i> @lang('Filter')</button>
            </div>
            <div class="card custom--card responsive-filter-card mb-4">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">

                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Date')</label>
                                <x-date-picker class="form--control"/>
                            </div>

                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Type')</label>
                                <select class="form-select form--control" name="trx_type">
                                    <option value="">@lang('All')</option>
                                    <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                    <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Remark')</label>
                                <select class="form-select form--control" name="remark">
                                    <option value="">@lang('Any')</option>
                                    @foreach ($remarks as $remark)
                                        <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="align-self-end">
                                <button class="btn btn--base w-100"><i class="las la-filter"></i> @lang('Apply Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-end">

                    <form method="GET">
                        <div class="input-group">
                            <input class="form-control form--control" placeholder="@lang('TRX No.')" name="search" type="text" value="{{ request()->search }}">
                            <button type="submit" class="input-group-text"><i class="la la-search"></i></button>
                        </div>

                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md ">
                        <table class="custom--table table has-search-form">
                            <thead>
                                <tr>
                                    <th>@lang('TRX No.')</th>
                                    <th>@lang('Time')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td>
                                            #{{ $trx->trx }}
                                        </td>
                                        <td>
                                            {{ showDateTime($trx->created_at) }}
                                        </td>
                                        <td>
                                            <span class="@if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                                {{ $trx->trx_type }} {{ showAmount($trx->amount)}}
                                            </span>
                                        </td>
                                        <td>
                                            {{ showAmount($trx->post_balance) }}
                                        </td>
                                        <td>{{ $trx->details }}</td>
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
                @if ($transactions->hasPages())
                    <div class="card-footer">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a class="active" href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket.index') }}">@lang('Support Tickets')</a></li>
@endpush
