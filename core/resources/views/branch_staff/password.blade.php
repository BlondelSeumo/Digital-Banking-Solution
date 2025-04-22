@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        <div class="col-xxl-9 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Update Password')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Current Password')</label>
                            <input class="form-control" type="password" name="old_password" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('New Password')</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
