@extends('admin.layouts.app')
@push('topBar')
    @include('admin.staff.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card viser--table overflow-hidden">
                <div class="card-header d-flex justify-content-end table-options">
                    <x-search-form btn="btn--light border h-auto"/>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ showDateTime($role->created_at) }}</td>
                                        <td>
                                            @can('admin.roles.edit')
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline--primary"><i class="la la-pencil"></i>@lang('Edit')</a>
                                            @endcan
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
            </div>
        </div>
    </div>
@endsection

@can('admin.roles.add')
    @push('breadcrumb-plugins')
        <a href="{{ route('admin.roles.add') }}" class="btn btn-outline--primary"><i class="la la-plus"></i> @lang('Add New')</a>
    @endpush
@endcan
