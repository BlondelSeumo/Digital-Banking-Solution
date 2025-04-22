@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Address')</th>
                                    <th>@lang('Designation')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffs as $branch)
                                    <tr>
                                        <td>{{ $loop->index + $staffs->firstItem() }}</td>
                                        <td>{{ __($branch->name) }}</td>
                                        <td>{{ __($branch->email) }}</td>
                                        <td>{{ __(@$branch->mobile) }}</td>
                                        <td>{{ __($branch->address) }}</td>
                                        <td>
                                            @if ($branch->designation)
                                                <span class="badge badge--success">@lang('Manager')</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Staff')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($branch->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $branch }}" data-modal_title="@lang('Edit Staff')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                            @if ($branch->resume)
                                                <a href="{{ route('admin.download.attachment', encrypt(getFilePath('branchStaff') . '/' . $branch->resume)) }}" class="btn btn-sm btn-outline--info">
                                                    <i class="las la-file"></i> @lang('Resume')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($staffs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($staffs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Update Modal -->
    <div class="modal fade" id="cuModal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.staff.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" required name="name" value="{{ old('name') }}" placeholder="@lang('Staff Name')">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="@lang('Staff Email')">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Mobile')</label>
                                    <input type="tel" class="form-control" name="mobile" value="{{ old('mobile') }}" required placeholder="@lang('Staff Mobile')">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Address')</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}" required placeholder="@lang('Staff Address')">
                        </div>
                        <div class="form-group">
                            <label class="required">@lang('Password')</label>
                            <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="@lang('Staff Password')">
                        </div>
                        <div class="form-group">
                            <label>@lang('Staff Resume/CV')</label>
                            <input type="file" accept=".pdf, .docx" class="form-control" name="resume" placeholder="@lang('Staff Resume/CV')">
                            <small class="mt-2  ">@lang('Supported files'): <b>@lang('pdf'), @lang('docx').</b> </small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Designation')</label>
                            <select name="designation" class="form-control" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="1">@lang('Branch Manager')</option>
                                <option value="0">@lang('Branch Staff')</option>
                            </select>
                        </div>
                        <div class="status"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary h-45 me-2 mb-2 cuModalBtn" data-modal_title="@lang('Add Branch Staff')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
    <form class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control bg--white" placeholder="@lang('Staff Email, Mobile')">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </form>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.cuModalBtn').on('click', function(e) {
                let data = $(this).data('resource');
                if (data) {
                    $('input[name=password]').closest('.form-group').addClass('d-none');
                    $(`select[name=designation]`).val(data.designation)
                } else {
                    $('input[name=password]').closest('.form-group').removeClass('d-none');
                }
            });
        })(jQuery);
    </script>
@endpush
