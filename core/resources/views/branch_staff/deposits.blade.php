@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('TRX No.')</th>
                                    <th>@lang('Account No.')</th>
                                    <th>@lang('Account Name')</th>

                                    @if (isManager())
                                        <th>@lang('Account Officer')</th>
                                    @endif

                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->trx }}</td>

                                        <td>
                                            <a href="{{ route('staff.account.detail', $deposit->user->account_number) }}">
                                                {{ @$deposit->user->account_number }}
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('staff.account.detail', $deposit->user->account_number) }}">
                                                {{ @$deposit->user->fullname }}
                                            </a>
                                        </td>

                                        @if (isManager())
                                            <td>
                                                <a href="{{ route('staff.profile.other', $deposit->branchStaff->id) }}">
                                                    {{ @$deposit->branchStaff->name }}
                                                </a>
                                            </td>
                                        @endif
                                        <td>{{ showDateTime($deposit->created_at, 'd M Y, h:i A') }}</td>
                                        <td>
                                            {{ showAmount($deposit->amount) }}
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
                @if ($deposits->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($deposits) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch="yes" />
@endpush
