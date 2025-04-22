@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="mb-3 text-end">
        <a class="btn btn-sm btn--base" href="{{ route('ticket.open') }}">
            <i class="las la-plus"></i>
            @lang('Open New Ticket')
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md">
                        <table class="custom--table table">
                            <thead>
                                <tr>
                                    <th>@lang('Ticket ID')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supports as $support)
                                    <tr>

                                        <td>
                                            #{{ $support->ticket }}
                                        </td>

                                        <td>
                                            {{ strLimit($support->subject, 20) }}
                                        </td>

                                        <td>@php echo $support->statusBadge; @endphp</td>

                                        <td>
                                            @php
                                                echo $support->priorityBadge;
                                            @endphp
                                        </td>

                                        <td>{{ diffForHumans($support->last_reply) }} </td>

                                        <td>
                                            <a class="btn btn-outline--base btn-sm" href="{{ route('ticket.view', $support->ticket) }}">
                                                <i class="la la-desktop"></i> @lang('View')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($supports->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($supports) }}
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
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="active" href="{{ route('ticket.index') }}">@lang('Support Tickets')</a></li>
@endpush
