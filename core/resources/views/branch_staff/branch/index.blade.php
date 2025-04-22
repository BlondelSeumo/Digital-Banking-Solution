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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Address')</th>
                                    <th>@lang('Email | Mobile')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td>{{ $loop->index + $branches->firstItem() }}</td>
                                        <td>{{ __($branch->name) }}</td>
                                        <td>{{ __($branch->code) }}</td>
                                        <td>{{ __($branch->address) }}</td>
                                        <td>
                                            <span class="d-block">{{ @$branch->email }}</span>
                                            <span>{{ @$branch->mobile }}</span>
                                        </td>
                                        <td>
                                            @if ($branch->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $branch }}" data-modal_title="@lang('Edit Branch')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
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
                @if ($branches->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($branches) }}
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
                <form action="{{ route('admin.branch.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" required name="name" value="{{ old('name') }}" placeholder="@lang('Branch Name')">
                        </div>
                        <div class="form-group">
                            <label>@lang('Code')</label>
                            <input type="text" class="form-control" name="code" value="{{ old('code') }}" required placeholder="@lang('Branch Code')">
                        </div>
                        <div class="form-group">
                            <label>@lang('Address')</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}" required placeholder="@lang('Branch Address')">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('Branch Email')">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Mobile')</label>
                                    <input type="tel" class="form-control" name="mobile" value="{{ old('mobile') }}" placeholder="@lang('Branch Mobile')">
                                </div>
                            </div>
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
    <button type="button" class="btn btn-outline--primary h-45 me-2 mb-2 cuModalBtn" data-modal_title="@lang('Create New Branch')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
    <form class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control bg--white" placeholder="@lang('Branch Name,Code...')">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </form>
@endpush
