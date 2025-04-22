@if (gs()->modules->fdr)
    @php
        $content = getContent('fdr_plans.content', true);
        $totalPlans = App\Models\FdrPlan::active()->count();
        $plans = App\Models\FdrPlan::active()
            ->latest()
            ->limit(3)
            ->get();
    @endphp

    @if ($content && $plans->count())
        <section class="pt-100 pb-100">
            <div class="container-md">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10">
                        <div class="section-header text-center">
                            <div class="section-top-title border-left text--base">{{ __(@$content->data_values->heading) }}</div>
                            <h2 class="section-title">{{ __(@$content->data_values->subheading) }}</h2>
                        </div>
                    </div>
                </div>
                @include($activeTemplate . 'partials.fdr_plans')

                @if($totalPlans > 3 )
                <div class="text-center mt-4">
                    <a href="{{ route('user.fdr.plans') }}" class="btn btn--base">@lang('View All')</a>
                </div>
                @endif
            </div>
        </section>
    @endif
@endif
