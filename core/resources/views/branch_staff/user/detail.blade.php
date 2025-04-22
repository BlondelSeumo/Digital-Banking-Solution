@extends('branch_staff.layouts.app')
@section('panel')
    <div class="d-flex gap-3 mb-4 flex-wrap">
        <div class="flex-fill">
            <x-widget style="2" color="primary" title="Account Number" value="{{ $user->account_number }}" icon="la la-user" icon_style="solid" />
        </div>

        <div class="flex-fill">
            <x-widget style="2" color="success" title="Balance" value="{{ showAmount($user->balance) }} {{ gs()->cur_text }}" icon="la la-money" icon_style="solid" />
        </div>

        <div class="flex-fill">
            <x-widget style="2" color="danger" title="Branch Name" value="{{ $user->branch->name ?? 'Online' }}" icon="la la-map-marker" icon_style="solid" />
        </div>
    </div>

    <div class="row gy-4">

        <div class="col-xl-4 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-center gap-3">
                        <h6>
                            @if ($user->ev)
                                <i class="la la-check-circle text--success"></i>
                            @else
                                <i class="la la-times-circle text--danger"></i>
                            @endif
                            @lang('Email')
                        </h6>
                        <h6>
                            @if ($user->sv)
                                <i class="la la-check-circle text--success"></i>
                            @else
                                <i class="la la-times-circle text--danger"></i>
                            @endif
                            @lang('Mobile')
                        </h6>
                        <h6>
                            @if ($user->kv)
                                <i class="la la-check-circle text--success"></i>
                            @else
                                <i class="la la-times-circle text--danger"></i>
                            @endif
                            @lang('KYC')
                        </h6>
                    </div>
                </div>
                <div class="card-body text-center">
                    <img class="account-holder-image rounded border" src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="account-holder-image">
                </div>
            </div>

            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Documents for Verification')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if ($user->kyc_data)
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                    <small class="text-muted">{{ __($val->name) }}</small>
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            <a class="ms-3" href="{{ route('staff.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"><i class="fa fa-file"></i> @lang('View File') </a>
                                        @else
                                            <h6>{{ __($val->value) }}</h6>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Basic Information')</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0">
                            <small class="text-muted">@lang('Account Status')</small>
                            @if ($user->status)
                                <span class="bg--success py-1 px-3 rounded"> <i class="la la-check-circle"></i> @lang('Active')</span>
                            @else
                                <span class="bg--danger py-1 px-3 rounded"> <i class="la la-ban"></i> @lang('Banned')</span>
                            @endif
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('First Name')</small>
                            <h6>{{ $user->firstname }}</h6>
                        </div>
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Last Name')</small>
                            <h6>{{ $user->lastname }}</h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Username')</small>
                            <h6>{{ $user->username }} </h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Email')</small>
                            <h6>{{ $user->email }} </h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Mobile Number')</small>
                            <h6>{{ $user->mobile }} </h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('City')</small>
                            <h6>{{ $user->city }}</h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('State')</small>
                            <h6>{{ $user->state }}</h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Zip/Postal')</small>
                            <h6>{{ $user->zip }}</h6>
                        </div>
                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Country')</small>
                            <h6>{{ $user->country_name }}</h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Address')</small>
                            <h6>{{ $user->address }}</h6>
                        </div>

                        <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                            <small class="text-muted">@lang('Joined On')</small>
                            <h6>{{ showDateTime($user->created_at, 'd M Y, h:i A') }}</h6>
                        </div>

                        @if ($user->branch)
                            <div class="list-group-item d-flex justify-content-between flex-wrap border-0">
                                <small class="text-muted">@lang('Registred By')</small>
                                <h6>{{ $user->branchStaff->name }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSubModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control" name="amount" type="number" value="{{ old('amount') }}" step="any" required>
                                <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if ($staff->designation == Status::ROLE_ACCOUNT_OFFICER)
        <button class="btn btn-lg btn--success btn--shadow deposit-btn" data-action="{{ route('staff.deposit.save', $user->account_number) }}">
            <i class="las la-plus-circle"></i> @lang('Deposit Money')
        </button>

        <button class="btn btn--danger btn--shadow withdraw-btn" data-action="{{ route('staff.withdraw.save', $user->account_number) }}">
            <i class="las la-minus-circle"></i> @lang('Withdraw Money')
        </button>
    @endif
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.deposit-btn').on('click', function() {
                var modal = $('#addSubModal');
                modal.find('.modal-title').text('Deposit Money');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });

            $('.withdraw-btn').on('click', function() {
                var modal = $('#addSubModal');
                modal.find('.modal-title').text('Withdraw Money');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .account-holder-image {
            height: 180px;
        }
    </style>
@endpush
