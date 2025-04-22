@extends($activeTemplate . 'user.loan.layout')
@section('loan-content')
    <div class="row gy-4">
        <div class="col-xl-4">
            <div class="card custom--card">
                <div class="card-body">
                    <h5 class="text-center">@lang('You are aplying to take loan')</h5>
                    <p class="text-center text--danger">(@lang('Be Sure Before Confirm'))</p>
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Plan Name')</span>
                            <span>@lang($plan->name)</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Loan Amount')</span>
                            <span>{{showAmount($amount) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Total Installment')</span>
                            <span>{{ $plan->total_installment }}</span>
                        </li>

                        @php $perInstallment = $amount * $plan->per_installment / 100; @endphp

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Per Installment')</span>
                            <span>{{showAmount($perInstallment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between text--danger">
                            <span class="fw-bold">@lang('You Need To Pay')</span>
                            <span class="fw-bold">{{showAmount($perInstallment * $plan->total_installment) }}</span>
                        </li>
                    </ul>

                    <p class="p-2">
                        @if ($plan->delay_value && getAmount($plan->delay_charge))
                            <small class="text--danger">*
                                @lang('If an installment is delayed for')
                                <span class="fw-bold">{{ $plan->delay_value }}</span> @lang('or more days then, an amount of'), <span class="fw-bold">{{showAmount($plan->delay_charge) }}</span> @lang('will be applied for each day.')
                            </small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Application Form')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.loan.apply.confirm') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($plan->instruction)
                            <div class="form-group">
                                <p class="rounded p-3">
                                    @php echo $plan->instruction @endphp
                                </p>
                            </div>
                        @endif
                        <x-viser-form identifier="id" identifierValue="{{ $plan->form_id }}" />
                        <button type="submit" class="btn btn--base w-100">@lang('Apply')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
