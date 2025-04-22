@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 bg_img" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/bg1.jpg') }} ');">
        <div class="container">
            <div class="row gy-4 justify-content-center pb-50">
                <div class="table-responsive--md">
                    <table class="custom--table table">
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
                                    <td>{{ $loop->index + $branches->firstItem() }}</td>
                                    <td>{{ __($branch->name) }}</td>
                                    <td>{{ __($branch->address) }}</td>
                                    <td>{{ $branch->email }}</td>
                                    <td>{{ $branch->mobile }}</td>
                                    <td>{{ $branch->routing_number }}</td>
                                    <td>
                                        <button class="btn btn-outline--base btn-sm show-map-btn" data-name="{{ $branch->name }}" data-map_location="{{ $branch->map_location }}">
                                            <i class="las la-map-marked-alt"></i>
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
                @if ($branches->hasPages())
                    <div class="mt-3">
                        {{ $branches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('modal')
    <div class="modal fade" id="mapModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="text-center">@lang('Map not available for this branch')</p>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.show-map-btn').on('click', function() {
                var modal = $('#mapModal');
                modal.find('.modal-title').text(`${$(this).data('name')} Branch`);
                if ($(this).data('map_location')) {
                    modal.find('.modal-body').html($(this).data('map_location'));
                }
                modal.find('iframe').css('width', '100%')
                modal.modal('show')
            });
        })(jQuery)
    </script>
@endpush
