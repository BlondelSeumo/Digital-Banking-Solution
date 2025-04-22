@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        <div class="col-xxl-9 col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row gy-3 gx-4 justify-content-center">
                        <div class="col-xl-4 col-lg-5">
                            <div class="text-center p-3">
                                <img src="{{ getImage(getFilePath('branchStaffProfile') . '/' . $staff->image, null, true) }}" class="border " alt="profile-image">
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-7">
                            <div class="d-flex align-items-center h-100">
                                <div class="list-group list-group-flush w-100">
                                    <div class="d-flex py-3 list-group-item flex-wrap justify-content-between">
                                        <span>@lang('Name')</span>
                                        <h6>{{ $staff->name }}</h6>
                                    </div>
                                    <div class="d-flex py-3 list-group-item flex-wrap justify-content-between">
                                        <span>@lang('Email')</span>
                                        <h6>{{ $staff->email }}</h6>
                                    </div>
                                    <div class="d-flex py-3 list-group-item flex-wrap justify-content-between">
                                        <span>@lang('Mobile')</span>
                                        <h6>{{ $staff->mobile }}</h6>
                                    </div>
                                    <div class="d-flex py-3 list-group-item flex-wrap justify-content-between">
                                        <span>@lang('Address')</span>
                                        <h6>{{ $staff->address }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
