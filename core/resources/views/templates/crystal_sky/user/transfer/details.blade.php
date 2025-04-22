@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <h5 class="text-center mb-3">@lang('Transaction Successful')</h5>
                    @include('partials.user.transfer_details')
                </div>

                <div class="card-footer p-3 text-end">
                    <a href="{{ route('user.transfer.details', $transfer->trx) }}?download" type="button" class="btn btn--base btn-sm"><i class="las la-file-download"></i> @lang('Download')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .caption-list-two li {
            border-bottom: 1px solid #f3f3f3;
            text-align: end;
        }
    </style>
@endpush
