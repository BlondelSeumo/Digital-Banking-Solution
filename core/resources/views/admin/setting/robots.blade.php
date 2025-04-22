@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6>@lang('Insert Robots txt')</h6>
                </div>
                <form method="post" action="{{ route('admin.setting.robot.submit') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" rows="10" name="robots">{{ $fileContent }}</textarea>
                        </div>
                    </div>
                    @can('admin.setting.robot.submit')
                        <div class="card-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
@endsection
