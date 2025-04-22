@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 justify-content-center">
        <div class="col-md-8">

            @can('admin.setting.system.configuration')
                <small class="text-muted d-block mb-3 text-end">
                    @lang('You may ENABLE / DISABLE the referral system from the') <a href="{{ route('admin.setting.system.configuration') }}">@lang('System Configuration')</a>
                </small>
            @endcan

            <div class="card">
                @if ($levels->count())
                    <div class="card-header">
                        <h5 class="card-title">
                            @lang('Update Referral Level Setting')
                        </h5>
                    </div>
                @endif

                <div class="card-body parent">

                    <form action="{{ route('admin.referral.setting.save') }}" method="post" id="referralForm">
                        @csrf

                        <div class="mb-3">
                            <label for="commission_count">@lang('Commission Count') <i class="la la-info-circle" title="@lang('The number of times referrers will get the referral commission from a referee.')"></i></label>
                            <div class="input-group">
                                <input type="number" name="commission_count" id="commission_count" class="form-control" value="{{ old('commission_count', gs()->referral_commission_count) }}">
                                <span class="input-group-text">@lang('Times')</span>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="commission_count">@lang('Number of Levels')</label>
                            <div class="input-group">
                                <input type="number" name="level" @if ($levels->count()) value="{{ $levels->count() }}" @endif placeholder="@lang('Number of Level')" class="form-control">
                                <button type="button" class="input-group-text border-0 btn btn--primary btn-block generate">
                                    @if ($levels->count())
                                        @lang('Regenerate')
                                    @else
                                        @lang('Generate')
                                    @endif
                                </button>
                            </div>
                        </div>

                        <div class="levelForm">
                            <div class="form-group">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="levels-container">

                                            @foreach ($levels as $level)
                                                <div class="input-group mt-3">
                                                    <span class="input-group-text no-right-border">@lang('LEVEL') {{ $level->level }}</span>

                                                    <input name="commission[{{ $level->level }}][percent]" class="form-control" value="{{ getAmount($level->percent) }}" type="number" step="any" required placeholder="Commission Percentage">

                                                    <span class="input-group-text">%</span>
                                                    <button class="input-group-text border-0 btn btn--danger removeBtn" type="button"><i class='fa fa-times'></i></button>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @can('admin.referral.setting.save')
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                            @endcan
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var max = 1;

            const generateLevels = () => {
                const levels = $('[name=level]').val();

                var html = '';

                if (levels == '' || levels <= 0) {
                    notify('error', "@lang('Level field is required')");
                    return;
                }

                for (let i = 1; i <= parseInt(levels); i++) {
                    html += `
                        <div class="input-group mt-4">
                            <span class="input-group-text no-right-border">LEVEL ${i}</span>
                            <input name="commission[${i}][percent]" class="form-control" type="number" step="any" required placeholder="@lang('Commission Percentage')">
                            <span class="input-group-text">%</span>
                            <button class="input-group-text border-0 btn btn--danger removeBtn" type="button"><i class='fa fa-times'></i></button>
                        </div>`;

                }

                $('.levels-container').html(html);
            }

            $(".generate").on('click', function() {
                generateLevels();
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.input-group').remove();

                $('.levels-container .input-group').each((index, element)=>{
                    $(element).find('.input-group-text').first().text(`Level ${index+1}`);
                });

            });

            $('[name=level]').on('focus', function() {
                $('#referralForm').on('submit', function(e) {
                    e.preventDefault();
                    generateLevels();
                });
            });

            $('[name=level]').on('blur', function() {
                $('#referralForm').off('submit');
            });
        })(jQuery);
    </script>
@endpush
