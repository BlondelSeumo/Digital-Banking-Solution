@extends($activeTemplate . 'user.loan.layout')
@section('loan-content')
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
