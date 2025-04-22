<div class="card b-radius--10">
    @if (@$branch)
        <div class="card-header">
            <h5 class="card-title">@lang('Update Branch Info')</h5>
        </div>
    @endif
    <form action="{{ route('admin.branch.save', @$branch->id) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Branch Name')</label>
                        <input class="form-control" name="name" type="text" value="{{ old('name', @$branch->name) }}" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Branch Code')</label>
                        <input class="form-control" name="code" type="text" value="{{ old('code', @$branch->code) }}" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Routing Number')</label>
                        <input class="form-control" name="routing_number" type="number" value="{{ old('routing_number', @$branch->routing_number) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('SWIFT Code')</label>
                        <input class="form-control" name="swift_code" type="text" value="{{ old('swift_code', @$branch->swift_code) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Phone')</label>
                        <input class="form-control" name="phone" type="tel" value="{{ old('phone', @$branch->phone) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Mobile')</label>
                        <input class="form-control" name="mobile" type="tel" value="{{ old('mobile', @$branch->mobile) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Email')</label>
                        <input class="form-control" name="email" type="email" value="{{ old('email', @$branch->email) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Fax')</label>
                        <input class="form-control" name="fax" type="text" value="{{ old('fax', @$branch->fax) }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Address')</label>
                        <textarea class="form-control" name="address" type="text" rows="6" required>{{ old('address', @$branch->address) }}</textarea>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-flex justify-content-between align-items-center">
                            @lang('Google Map Location')

                            @if (@$branch->map_location)
                                <button type="button" class="btn btn--sm btn-outline--primary btn-sm btn-view-map" data-bs-toggle="modal" data-bs-target="#viewMapModal"><i class="la la-eye"></i>@lang('View')</button>
                            @endif
                        </label>

                        <textarea class="form-control" name="map_location" rows="6">{{ old('map_location', @$branch->map_location) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        @can('admin.branch.save')
            <div class="card-footer">
                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
            </div>
        @endcan
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="viewMapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-end mb-3">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="overflow-hidden">
                    @php echo @$branch->map_location; @endphp
                </div>
            </div>
        </div>
    </div>
</div>
