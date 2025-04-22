@if (gs()->modules->dps)
    @php
        $content = getContent('dps_plans.content', true);
        $totalPlans = App\Models\DpsPlan::active()->count();
        $plans = App\Models\DpsPlan::active()->orderBy('per_installment')->limit(3)->get();
    @endphp

    @if ($content && $plans->count())
        <section class="pt-100 pb-100 section--bg">
            <div class="container-md">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-7">
                        <div class="section-header text-center">
                            <div class="section-top-title border-left text--base">{{ __(@$content->data_values->heading) }}
                            </div>
                            <h2 class="section-title">{{ __(@$content->data_values->subheading) }}</h2>
                        </div>
                    </div>
                </div>
                @include($activeTemplate . 'partials.dps_plans')
                @if ($totalPlans > 3)
                    <div class="text-center mt-4">
                        <a href="{{ route('user.dps.plans') }}" class="btn btn--base">@lang('View All')</a>
                    </div>
                @endif
            </div>
        </section>
    @endif
@endif
