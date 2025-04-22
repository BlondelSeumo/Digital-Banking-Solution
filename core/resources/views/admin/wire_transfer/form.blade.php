@extends('admin.layouts.app')

@push('topBar')
    @include('admin.wire_transfer.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-sm btn-outline--primary float-end form-generate-btn">
                        <i class="la la-fw la-plus"></i>@lang('Add New')
                    </button>
                </div>

                <div class="card-body">
                    <form action="{{route('admin.wire.transfer.form.save')}}" method="post">
                        @csrf
                        <x-generated-form :form="@$form"/>

                        @can('admin.wire.transfer.form.save')
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-form-generator-modal />
@endsection
