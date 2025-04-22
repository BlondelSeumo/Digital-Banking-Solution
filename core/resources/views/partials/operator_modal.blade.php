@push('modal')
    <div class="modal custom--modal" id="operatorsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Select Operators')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-preloader d-none">
                        <div class="spinner-border text--base" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                    <ul class="nav nav-tabs operator--tab gap-2 gap-md-0" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link all active" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">@lang('All')</button>
                        </li>
                        <li class="nav-item recharge" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#recharge" type="button" role="tab" aria-controls="recharge" aria-selected="true">@lang('Recharge')</button>
                        </li>
                        <li class="nav-item bundle" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bundle" type="button" role="tab" aria-controls="bundle" aria-selected="false">@lang('Bundle')</button>
                        </li>
                        <li class="nav-item data" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="false">@lang('Data')</button>
                        </li>
                        <li class="nav-item pin" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pin" type="button" role="tab" aria-controls="pin" aria-selected="false">@lang('Pin')</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active all" id="all" role="tabpanel">
                            <div class="operator-wrapper all"></div>
                        </div>
                        <div class="tab-pane fade recharge" id="recharge" role="tabpanel">
                            <div class="operator-wrapper recharge"></div>
                        </div>
                        <div class="tab-pane fade bundle" id="bundle" role="tabpanel">
                            <div class="operator-wrapper bundle"></div>
                        </div>
                        <div class="tab-pane fade data" id="data" role="tabpanel">
                            <div class="operator-wrapper data"></div>
                        </div>
                        <div class="tab-pane fade pin" id="pin" role="tabpanel">
                            <div class="operator-wrapper pin"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn--base btn-md m-0 confirmOperatorBtn" type="button">@lang('Confirm')</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('style')
    <style>
        .modal-preloader {
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgb(255 255 255 / 80%);
            z-index: 999;
            display: grid;
            place-content: center;
            top: 0;
            left: 0;
        }

        #operatorsModal .modal-body {
            position: unset !important;
        }
    </style>
@endpush
