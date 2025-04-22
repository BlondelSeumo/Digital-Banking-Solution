@if (gs()->modules->loan)
    @php
        $loanContent = getContent('loan_plans.content', true);
        $plans = App\Models\LoanPlan::active()
            ->latest()
            ->limit(3)
            ->get();
    @endphp
    @if ($loanContent && $plans->count())
        <section class="py-120 pricing section-bg-light bg-img" data-background-image="{{ asset($activeTemplateTrue . 'images/thumbs/pricing-bg.png') }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h6 class="section-heading__subtitle">{{ __(@$loanContent->data_values->title) }}</h6>
                            <h2 class="section-heading__title">{{ __(@$loanContent->data_values->heading) }}</h2>
                        </div>
                    </div>
                </div>
                @include($activeTemplate . 'partials.loan_plans')
                <div class="text-center mt-4">
                    <a href="{{ route('user.loan.plans') }}" class="btn btn--base">@lang('View All')</a>
                </div>
            </div>
        </section>
    @endif
@endif
