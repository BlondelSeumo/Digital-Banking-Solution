@if (gs()->modules->fdr)
    @php
        $content = getContent('fdr_plans.content', true);
        $plans = App\Models\FdrPlan::active()
            ->latest()
            ->limit(3)
            ->get();
    @endphp

    @if ($content && $plans->count())
        <section class="py-120 pricing  bg-img" data-background-image="{{ asset($activeTemplateTrue . 'images/thumbs/pricing-bg.png') }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h6 class="section-heading__subtitle">{{ __(@$content->data_values->heading) }}</h6>
                            <h2 class="section-heading__title">{{ __(@$content->data_values->subheading) }}</h2>
                        </div>
                    </div>
                </div>
                @include($activeTemplate . 'partials.fdr_plans')
                <div class="text-center mt-4">
                    <a href="{{ route('user.fdr.plans') }}" class="btn btn--base">@lang('View All')</a>
                </div>
            </div>
        </section>
    @endif
@endif
