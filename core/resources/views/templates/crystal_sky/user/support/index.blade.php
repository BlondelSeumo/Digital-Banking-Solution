@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="card custom--card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
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
                                <a class="btn btn-outline--base btn--sm" href="{{ route('ticket.view', $support->ticket) }}">
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
@endsection

@push('bottom-menu')
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('ticket.index') }}" class="btn btn--base active">@lang('My Tickets')</a>
            <a href="{{ route('ticket.open') }}" class="btn btn-outline--base">@lang('Open New Ticket')</a>
        </div>
    </div>
@endpush


@push('style')
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 1.875rem;
        }
    </style>
@endpush
