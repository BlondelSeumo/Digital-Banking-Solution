@if (!request()->routeIs('home'))
    @php $breadCumImage = getContent('breadcrumb.content', true); @endphp

    <section class="inner-hero bg_img overlay--one" style="background-image: url('{{ getImage('assets/images/frontend/breadcrumb/' . @$breadCumImage->data_values->image, '1920x1280') }}');">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="page-title text-white">{{ __($pageTitle) }}</h2>
                </div>
            </div>
        </div>
    </section>
@endif
