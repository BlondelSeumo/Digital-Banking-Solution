@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="user-profile-wrapper">
        <div class="profile-info">
            <div class="card custom--card">
                <div class="card-body">
                    <div class="proifle-image-preview">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="profile-image">
                    </div>

                    <ul class="caption-list-two mt-4">
                        <li>
                            <span class="caption">@lang('Account No.')</span>
                            <span class="value">{{ $user->account_number }}</span>
                        </li>

                        @if ($user->branch)
                            <li>
                                <span class="caption">@lang('Branch')</span>
                                <span class="value">{{ $user->branch->name }}</span>
                            </li>
                        @endif

                        <li>
                            <span class="caption">@lang('Username')</span>
                            <span class="value">{{ $user->username }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Email')</span>
                            <span class="value">{{ $user->email }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Mobile')</span>
                            <span class="value">+{{ $user->mobile }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Country')</span>
                            <span class="value">{{ __($user->country_name) }}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="profile-form">
            <div class="card custom--card">

                <div class="card-body">
                    <form class="register" action="" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input class="form--control" name="firstname" type="text" value="{{ $user->firstname }}" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input class="form--control" name="lastname" type="text" value="{{ $user->lastname }}" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('State')</label>
                                    <input class="form--control" name="state" type="text" value="{{ $user->state }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('City')</label>
                                    <input class="form--control" name="city" type="text" value="{{ $user->city }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Zip Code')</label>
                                    <input class="form--control" name="zip" type="text" value="{{ $user->zip }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Address')</label>
                                    <input class="form--control" name="address" type="text" value="{{ $user->address }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <input class="form--control" id="imageUpload" name="image" type='file' accept=".png, .jpg, .jpeg" />
                                    @lang('For optimal results, please upload an image with a 3.5:3 aspect ratio, which will be resized to') {{getFileSize('userProfile')}} @lang('pixels')
                                </div>
                            </div>

                        </div>
                        <button class="btn btn-md btn--base w-100" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .user-profile-wrapper {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .profile-info {
            width: 320px;
        }

        .profile-form {
            width: calc(100% - 335px);
        }

        @media(max-width:767px) {
            .user-profile-wrapper {
                gap: 10px;
            }

            .profile-info {
                width: 380px;
            }

            .profile-form {
                width: 380px;
            }
        }

        @media(max-width:590px) {
            .profile-info {
                width: 300px;
            }

            .profile-form {
                width: 300px;
            }
        }

        .proifle-image-preview {
            text-align: center;
        }

        .proifle-image-preview img {
            width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 5px;
        }

        .caption-list-two {
            padding: 0;
        }
    </style>
@endpush
@push('script')
    <script>
        $("#imageUpload").on('change', function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.proifle-image-preview img').attr('src', e.target.result)
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endpush

@push('bottom-menu')
    <li>
        <a class="active" href="{{ route('user.profile.setting') }}">@lang('Profile')</a>
    </li>

    @if (gs()->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif

    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket.index') }}">@lang('Support Tickets')</a></li>
@endpush
