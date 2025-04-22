@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            @include('admin.branch.form')
        </div>
    </div>
@endsection

@can('admin.branch.index')
    @push('breadcrumb-plugins')
    <a href="{{ route('admin.branch.index') }}" class="btn btn-sm btn-outline--dark">
        <i class="la la-list"></i>@lang('All Branches')
    </a>
    @endpush
@endcan
