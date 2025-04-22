@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Account No.')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Account Name')</th>

                                    @if (isManager())
                                        <th>@lang('Branch')</th>
                                        <th>@lang('Registered By')</th>
                                    @endif

                                    <th>@lang('Registered At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                    <tr>
                                        <td>{{ $loop->index + $accounts->firstItem() }}</td>

                                        <td>
                                            <a href="{{ route('staff.account.detail', $account->account_number) }}">{{ $account->account_number }}</a>
                                        </td>

                                        <td>
                                            <a href="{{ route('staff.account.detail', $account->account_number) }}">{{ $account->username }}</a>
                                        </td>

                                        <td>
                                            {{ $account->fullname }}
                                        </td>

                                        @if (isManager())
                                            <td>
                                                @if ($account->branch_id)
                                                    <span>{{ __(@$account->branch->name) }}</span>
                                                @else
                                                    <span>@lang('Online')</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($account->branchStaff)
                                                    <a href="{{ route('staff.profile.other', $account->branchStaff->id) }}">
                                                        {{ $account->branchStaff->name }}
                                                    </a>
                                                @else
                                                    @lang('Online')
                                                @endif
                                            </td>
                                        @endif

                                        <td>{{ showDateTime($account->created_at) }} </td>

                                        <td>
                                            @if ($staff->designation == Status::ROLE_ACCOUNT_OFFICER)
                                                <a class="btn btn-sm btn-outline--primary" href="{{ route('staff.account.edit', $account->account_number) }}">
                                                    <i class="las la-edit"></i>@lang('Edit')
                                                </a>
                                            @endif

                                            <a class="btn btn-sm btn-outline--info" href="{{ route('staff.account.detail', $account->account_number) }}">
                                                <i class="las la-desktop"></i>@lang('Details')
                                            </a>
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
                @if ($accounts->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($accounts) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if ($staff->designation == Status::ROLE_MANAGER)
        <div class="btn-group">
            <button class="btn btn-outline--primary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                @if ($branchId)
                    @php $branch = $branches->where('id', $branchId)->first(); @endphp
                    {{ @$branch->name }}
                @else
                    @lang('All Branch')
                @endif
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['branch' => null]) }}">@lang('All Branch')</a>
                </li>
                @foreach ($branches as $branch)
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['branch' => $branch->id]) }}">{{ __($branch->name) }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    <x-search-form />
@endpush
