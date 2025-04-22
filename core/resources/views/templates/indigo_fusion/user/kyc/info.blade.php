@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if ($user->kv == Status::KYC_PENDING)
                <div class="card custom--card  mb-4">
                    <div class="card-body">
                        <h5 class="mb-2 text--success">
                            @lang('Thank You for Submitting Your KYC Information!')
                        </h5>
                        <p class="text-muted">
                            @lang('We appreciate your cooperation in completing the KYC process. Your information has been successfully submitted and is now under review.')
                        </p>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                <div class="card custom--card  mb-4">
                    <div class="card-body">
                        <h5 class="mb-2 text--danger">
                            @lang('KYC Rejection Notice!')
                        </h5>
                        <p class="text-muted mb-2">
                            {{ $user->kyc_rejection_reason }}
                        </p>

                        {{ __(@$kyc->data_values->reject) }} <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit KYC Your Information').</a></p>
                    </div>
                </div>
            @endif

            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        @lang('KYC Information')
                    </h5>
                    @if ($user->kv == Status::VERIFIED)
                        <span class="badge badge--success">@lang('Approved')</span>
                    @endif
                </div>
                <div class="card-body">

                    @if ($user->kyc_data)
                        <ul class="list-group list-group-flush">
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    {{ __($val->name) }}
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="ms-3"><i class="fa fa-file"></i> @lang('View File') </a>
                                        @else
                                            <p>{{ __($val->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <h5 class="text-center">@lang('KYC data not found')</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
