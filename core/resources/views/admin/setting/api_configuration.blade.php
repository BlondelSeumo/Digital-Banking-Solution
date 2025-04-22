@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="api_header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <h5>@lang('Airtime API') </h5>
                        <button type="button" class="btn btn-sm btn-outline--dark" data-bs-toggle="modal" data-bs-target="#helpModal"><i class="la la-question"></i>@lang('Help')</button>
                    </div>

                    @include('admin.api_config.reloadly')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .api_header {
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ced4da;
        }
    </style>
@endpush
