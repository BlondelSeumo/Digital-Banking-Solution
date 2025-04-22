@extends('branch_staff.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Branch Name')</th>
                                    <th>@lang('Address')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Contact')</th>
                                    <th>@lang('Routing No.')</th>
                                    <th>@lang('Map')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ __($branch->name) }}</td>
                                        <td>{{ __($branch->address) }}</td>
                                        <td>{{ $branch->email }}</td>
                                        <td>{{ $branch->mobile }}</td>
                                        <td>{{ $branch->routing_number }}</td>
                                        <td>
                                            <button class="btn btn-outline--primary btn-sm show-map-btn" data-name="{{ $branch->name }}" data-map_location="{{ $branch->map_location }}" @disabled(!$branch->map_location)>
                                                <i class="las la-map-marked-alt"></i> @lang('Location')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mapModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.show-map-btn').on('click', function() {
                var modal = $('#mapModal');
                modal.find('.modal-title').text(`${$(this).data('name')} Branch`);
                modal.find('.modal-body').html($(this).data('map_location'));
                modal.find('iframe').css('width', '100%')
                modal.modal('show')
            });
        })(jQuery)
    </script>
@endpush
