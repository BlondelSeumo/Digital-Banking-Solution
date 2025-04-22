@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center">

                    <i class="la la-user-times la-8x text--danger"></i>
                    <h4 class="mt-3">@lang('This staff account is currently banned')</h4>
                </div>
            </div>
        </div>
    </div>
@endsection
