@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <div class="text-end">
                        @php
                            echo $loan->statusBadge;
                        @endphp
                    </div>
                    @include('partials.user.loan_details')
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a href="{{ route('user.loan.details', $loan->loan_number) }}?download" type="button" class="btn btn--base btn-sm"><i class="las la-file-download"></i> @lang('Download')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.loan.plans') }}">@lang('Loan Plans')</a></li>
    <li><a href="{{ route('user.loan.list') }}" class="active">@lang('My Loan List')</a></li>
@endpush
