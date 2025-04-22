@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center">

                <i class="la la-user-times la-8x text--danger"></i>
                <h4 class="mt-3">@lang('This account is currently banned')</h4>
            </div>
        </div>
    </div>
</div>
@endsection


@push('style')
<style>

    .sidebar__inner::after  {
        content: "";
        position: absolute;
        background-color: rgba(255, 255, 255, 0.356);
        backdrop-filter: blur(2px);
        height: 100%;
        width: 100%;
    }

</style>
@endpush
