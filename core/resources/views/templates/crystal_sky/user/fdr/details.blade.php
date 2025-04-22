@extends($activeTemplate . 'user.fdr.layout')
@section('fdr-content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <div class="text-end">
                        @php
                            echo $fdr->statusBadge;
                        @endphp
                    </div>
                    @include('partials.user.fdr_details')
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a href="{{ route('user.fdr.details', $fdr->fdr_number) }}?download" type="button" class="btn btn--base btn-sm"><i class="las la-file-download"></i> @lang('Download')</a>
                    </div>
                </div>
            </div>

            @if ($fdr->locked_date->endOfDay() < now() && $fdr->status == Status::FDR_RUNNING)
                <div class="card custom--card mt-3">
                    <div class="card-body text-center">
                        <p class="text--info d-flex gap-2 align-items-center text-start">
                            <i class="la la-info-circle la-2x"></i> @lang('The lock-in period for this FDR has passed. You may close the FDR now if you wish, or you will receive installment as you did previously.')
                        </p>
                        <button type="button" data-id="{{ $fdr->id }}" class="btn btn--dark btn-sm closeBtn mt-3">
                            <i class="fa fa-stop-circle"></i> @lang('Close Now')
                        </button>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-3 d-flex align-items-center gap-2" role="alert">
                    <i class="la la-info-circle la-2x"></i> @lang('The option to close this FDR will only be available after the lock-in period.')
                </div>
            @endif

        </div>
    </div>
@endsection


@push('modal')
    <div class="modal fade" id="closeFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Close FDR')</h5>
                    <button type="button" class="bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" class="transferId" required>
                        </div>
                        <div class="content">
                            <p>@lang('Are you sure to close this FDR?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger text-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-md bg--base text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('.closeBtn').on('click', function() {
                let modal = $('#closeFdr');
                let form = modal.find('form')[0];
                form.action = `{{ route('user.fdr.close', '') }}/${$(this).data('id')}`
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
