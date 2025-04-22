@extends('admin.layouts.app')
@section('panel')

    <div class="row gy-4">
        <div class="col-xl-4 col-md-6 mb-30 d-none d-lg-block">

            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center border-bottom p-3">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('adminProfile').'/'. $admin->image, null, true)}}" alt="Image">
                        </div>
                        <div class="ps-3">
                            <h4>{{__($admin->name)}}</h4>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between py-3 align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{__($admin->name)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between py-3 align-items-center">
                            @lang('Username')
                            <span  class="fw-bold">{{__($admin->username)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between py-3 align-items-center">
                            @lang('Email')
                            <span  class="fw-bold">{{$admin->email}}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Profile Information')</h5>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xxl-4 col-xl-6 col-md-5 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader image="{{ $admin->image }}" class="w-100" type="adminProfile" :required=false />
                                </div>
                            </div>
                            <div class="col-xxl-8 col-xl-6 col-md-7 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" value="{{ $admin->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ $admin->email }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.password')}}" class="btn btn-sm btn-outline--primary"><i class="las la-key"></i>@lang('Password Setting')</a>
@endpush
@push('style')
    <style>
        .list-group-item:first-child{
            border-top-left-radius:unset;
            border-top-right-radius:unset;
        }
    </style>
@endpush
