@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="d-flex flex-wrap justify-content-end mb-4 gap-2">
        <a class="btn h-45 btn--base" href="{{ route('user.withdraw') }}">
            <i class="las la-wallet"></i> @lang('Withdraw Money')
        </a>
        <x-search-form placeholder="TRX No." btn="btn--base" />
    </div>

    <div class="card custom--card">
        <div class="card-body p-0">
            <div class="table-responsive--md">
                <table class="custom--table table">
                    <thead>
                        <tr>
                            <th>@lang('TRX No.')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('After Charge')</th>
                            <th>@lang('Initiated At')</th>
                            <th>@lang('Method')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($withdraws as $withdraw)
                            @php
                                $details = [];
                                foreach ($withdraw->withdraw_information ?? [] as $key => $info) {
                                    $details[] = $info;
                                    if ($info->type == 'file' && @$details[$key]) {
                                        $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                    }
                                }
                            @endphp

                            <tr>
                                <td>#{{ $withdraw->trx }}</td>

                                <td>{{ showAmount($withdraw->amount) }}</td>

                                <td>{{ showAmount($withdraw->charge) }}</td>

                                <td>{{ showAmount($withdraw->after_charge) }}</td>

                                <td>
                                    <em>{{ showDateTime($withdraw->created_at) }} </em>
                                </td>

                                <td>
                                    @if ($withdraw->branch)
                                        <span class="text--primary" title="@lang('Branch Name')">{{ __(@$withdraw->branch->name) }}</span>
                                    @else
                                        <span class="text--primary" title="@lang('Method Name')">{{ __(@$withdraw->method->name) }}</span>
                                    @endif
                                </td>

                                <td>
                                    @php echo $withdraw->statusBadge @endphp
                                </td>

                                <td>
                                    <a href="{{ route('user.withdraw.details', $withdraw->trx) }}" class="btn btn-sm btn-outline--base"><i class="la la-desktop"></i> @lang('Details')</a>
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
        @if ($withdraws->hasPages())
            <div class="card-footer">
                {{ $withdraws->links() }}
            </div>
        @endif
    </div>
@endsection
