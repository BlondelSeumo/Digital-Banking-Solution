@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        <div class="col-xxl-9 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-center">
                            <div class="col-xl-4 col-md-5 col-sm-6">
                                <div class="image-upload mb-3">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader image="{{ $staff->image }}" class="w-100" type="branchStaffProfile" :required=false />
                                </div>
                            </div>
                            <div class="col-xl-8 col-md-7 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" value="{{ $staff->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" value="{{ $staff->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Mobile')</label>
                                    <input class="form-control"  value="{{ $staff->mobile }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" value="{{ $staff->address }}" required>
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
