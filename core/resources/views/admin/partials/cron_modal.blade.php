<div id="cronModal" class="modal fade cron-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="cron-modal-header">
                    <div class="modal-header-top">
                        <h5 class="modal-title" id="exampleModalLongTitle"><i class="las la-calendar text--primary"></i> @lang('Please Set Cron Job') </h5>
                        <p>@lang('Once per 5-10 minutes is ideal while once every minute is the best option')</p>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="form-group">
                    <div class="justify-content-between d-flex flex-wrap mb-1">
                        <label class="fw-bold">@lang('Cron Command')</label>
                        <small class="fst-italic">
                            @lang('Last Cron Run'): <strong>{{ gs('last_cron') ? diffForHumans(gs('last_cron')) : 'N/A' }}</strong>
                        </small>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" id="cronPath" value="curl -s {{ route('cron') }}" readonly>
                        <button type="button" class="input-group-text copy-text-btn copyCronPath text--primary"><i class="fas fa-copy"></i> <span class="copyText text--primary">@lang('Copy')</span></button>
                    </div>
                </div>
                <div class="justify-content-between cron-btn-group d-flex gap-2">
                    @can('admin.cron.index')
                        <a href="{{ route('admin.cron.index') }}" class="btn btn-outline--primary  w-100 h-45"><i class="fas fa-cog"></i> @lang('Cron Job Setting')</a>
                    @endcan
                    <a href="{{ route('cron') }}?target=all" class="btn btn-outline--warning w-100 h-45"><i class="fas fa-bolt"></i> @lang('Run Manually')</a>
                </div>
            </div>

        </div>
    </div>
</div>

@push('style')
    <style>
        .cron-modal .modal-body {
            padding: 32px;
        }

        @media (max-width: 575px) {
            .cron-modal .modal-body {
                padding: 30px 15px;
            }
        }

        @media (max-width: 424px) {
            .cron-btn-group {
                flex-wrap: wrap;
            }
        }

        .cron-modal-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(222, 226, 230, 1);
            display: flex;
            gap: 10px;
            justify-content: space-between
        }

        .modal-header-top .modal-title i {
            font-size: 20px;
            color: hsl(var(--primary));
        }

        .modal-header-top .modal-title {
            font-weight: 700;
            font-size: 1.125rem;
        }

        .form-control[readonly],
        .form-control[disabled] {
            background-color: rgba(246, 246, 246, 1);
            pointer-events: none;
            border: none;
            border-radius: 5px !important;
        }

        .copy-text-btn {
            position: absolute;
            top: 50%;
            right: 0px;
            background: transparent;
            border: none;
            font-weight: 600;
            transform: translateY(-50%);
            font-size: 14px;
            z-index: 99;
            height: 100%;
            background: #f6f6f6;
        }

        .copy-text-btn i {
            margin-right: 5px;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .cron-btn-group a.h-45 {
            line-height: 2.3;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).on('click', '.copyCronPath', function() {
                var copyText = document.getElementById('cronPath');

                copyText.select();
                copyText.setSelectionRange(0, 99999);

                document.execCommand('copy');
                $(this).find('.copyText').text('Copied');
                setTimeout(() => {
                    $(this).find('.copyText').text('Copy');
                }, 2000);
            });


            @php
                $lastCron = Carbon\Carbon::parse(gs('last_cron'))->diffInSeconds();
            @endphp
            @if ($lastCron >= 900)
                setTimeout(() => {
                    $('#cronModal').modal('show');
                }, 1000);
            @endif

        })(jQuery)
    </script>
@endpush
