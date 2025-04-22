    <div class="row gy-4">
        <div class="col-md-12">
            <div class="mb-3">
                <p class="text--danger">@lang('Note: For proper calculation of exchange rates in this application, your Reloadly account\'s currency must match the site currency.')</p>
            </div>

            <form action="{{ route('admin.api.config.reloadly.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Client ID')</label>
                            <input class="form-control" name="credentials[client_id]" type="text" value="{{ $reloadly->credentials->client_id }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Client Secret')</label>
                            <input class="form-control" name="credentials[client_secret]" type="text" value="{{ $reloadly->credentials->client_secret }}" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="test_mode" id="testMode" @checked($reloadly->test_mode)>
                                <label class="form-check-label" for="testMode">
                                    @lang('Test Mode')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @can('admin.api.config.reloadly.save')
                    <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                @endcan
            </form>
        </div>
    </div>

    <div class="modal" id="helpModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Need Help')?</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('For using airtime top-up you need to follow the bellow steps'):</p>
                    <div class="instruction-wrapper">
                        <p class="instruction">
                            @lang('If you haven\'t registered for a Reloadly account yet, begin by creating one. Visit the registration page') <a target="_blank" href="https://www.reloadly.com/registration">@lang('here')</a> @lang('to sign up.')
                        </p>
                        <p class="instruction">
                            @lang('Once registration is complete, sign in to your Reloadly account. Navigate to the developers menu, where you\'ll find your API client ID and API client secret. Copy these credentials.')
                        </p>
                        <p class="instruction">
                            @lang('Fill out the form with the copied API client ID and API client secret.')
                        </p>
                        <p class="instruction">
                            @lang('Ensure that your Reloadly account has sufficient funds to support airtime top-up. Refer to their documentation') <a target="_blank" href="https://developers.reloadly.com/airtime/introduction">@lang('here')</a> @lang(' for more information.')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('style')
        <style>
            .instruction-wrapper {
                display: flex;
                flex-direction: column;
                gap: 10px;
                counter-reset: count;
            }

            .instruction::before {
                counter-increment: count;
                content: counters(count, ".") ".";
            }
        </style>
    @endpush
