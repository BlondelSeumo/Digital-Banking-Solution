@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.permissions.update') }}" method="POST">
        @csrf
        <div class="row gy-4">
            @php
                $i = 0;
            @endphp
            @foreach ($permissions as $key => $permissionGroups)
                <div class="col-12">
                    <div class="card b-radius--10">
                        <div class="card-header">
                            <h6 class="card-title m-0">{{ $key }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row gx-5">
                                @foreach ($permissionGroups as $permission)
                                    @php
                                        $route = $routes->where('name', $permission->code)->first();
                                        $lastCharacter = substr($permission->code, -1);
                                    @endphp
                                    <div class="col-lg-4">
                                        <div class="form-group ">
                                            <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                                                <label class="{{ $lastCharacter == '.' ? 'bg--danger' : 'text--cyan' }}">{{ $permission->code }}</label>
                                                <span class="badge @if (@$route['method'] == 'GET') bg--success @else bg--warning @endif">
                                                    {{ @$route['method'] }}
                                                </span>
                                            </div>
                                            <input type="hidden" name="permission[{{ $i }}][id]" value="{{ $permission->id }}">
                                            <div class="input-group w-auto">
                                                <span class="input-group-text">@lang('Name')</span>
                                                <input type="text" name="permission[{{ $i }}][name]" placeholder="Name" class="form-control" value="{{ ucwords($permission->name) }}">
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
        @can('admin.permissions.update')
            <button type="submit" class="btn btn--primary w-100 mt-3">@lang('Update')</button>
        @endcan
    </form>
@endsection
