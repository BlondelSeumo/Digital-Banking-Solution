@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center mt-4">
        <div class="col-xl-7 col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    <div class="text-end">
                        @php
                            echo $dps->statusBadge;
                        @endphp
                    </div>

                    @include('partials.user.dps_details')
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a href="{{ route('user.dps.details', $dps->dps_number) }}?download" class="btn btn--base btn-sm"><i class="las la-file-download"></i> @lang('Download')</a>
                    </div>
                </div>
            </div>

            @if ($dps->status == Status::DPS_MATURED)
                <div class="card custom--card mt-3">
                    <div class="card-body text-center">
                        <p class="text--info d-flex gap-2 align-items-center text-start">
                            <i class="la la-info-circle la-2x"></i> @lang('Your Deposit Pension Scheme (DPS) has matured. You can now withdraw the amount. Upon withdrawal, the maturity amount will be added to your main balance.')
                        </p>
                        <button type="button" class="btn btn-sm btn--base confirmationBtn mt-3" data-action="{{ route('user.dps.withdraw', $dps->id) }}" data-question="@lang('Are you sure to withdraw this DPS?')"><i class="la la-money-check"></i> @lang('Withdraw Now')</button>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-3 d-flex align-items-center gap-2" role="alert">
                    <i class="la la-info-circle la-2x"></i> @lang('You will have the option to withdraw this DPS after all required installments have been completed.')
                </div>
            @endif

        </div>
    </div>

    <x-confirmation-modal height="h-none" />
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.dps.plans') }}">@lang('DPS Plans')</a></li>
    <li><a href="{{ route('user.dps.list') }}" class="active">@lang('My DPS List')</a></li>
@endpush
