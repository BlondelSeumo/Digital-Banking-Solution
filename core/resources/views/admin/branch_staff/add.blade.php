@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            @include('admin.branch_staff.form')
        </div>
    </div>
@endsection

@can('admin.branch.staff.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.branch.staff.index') }}" />
    @endpush
@endcan
