@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <h6 class="mb-4">
                            @lang('First of all, you have to create an in-app purchase product non-consumable in the Play Store. we assume that you already created some non-consumable products in the Play Store console now we will show the process of how you can set enough necessary processes to verify in-app purchases')
                        </h6>
                        <h6 class="mb-2 ">
                            @lang('1. Enable APIs in Google Cloud Console')
                        </h6>
                        <div class="ms-3 mb-4">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Go to ') <a href="https://console.cloud.google.com/" target="_blank" class="text--primary">
                                        @lang('Google Cloud Console') <i class="fas fa-external-link-alt text--small"></i></a>
                                    @lang(' and create a new app, or select one')
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Now go to the ')
                                    <a href="https://console.cloud.google.com/apis/library/androidpublisher.googleapis.com" target="_blank" class="text--primary"> @lang('Google Play Android Developer API') <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    @lang('page and click on the enable button')
                                </li>
                                <li class="my-1"><i class="las la-dot-circle text--primary"></i>
                                    @lang('Go to the')
                                    <a href="https://console.cloud.google.com/apis/library/playdeveloperreporting.googleapis.com" target="_blank" class="text--primary"> @lang(' Google Play Developer Reporting API') <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    @lang(' page and click on the enable button')
                                </li>
                            </ul>
                        </div>
                        <h6 class="mb-2">@lang('2. Create a Service Account in the Google Cloud Console')</h6>
                        <div class="ms-3 mb-4">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Go to the ') <span class="fw-bold">@lang('Google Cloud console') <i class="fas fa-arrow-right"></i> @lang('IAM & Admin') <i class="fas fa-arrow-right"></i>
                                        <a href="https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts?supportedpurview=project" target="_blank" class="text--primary">@lang('Service Accounts') <i class="fas fa-external-link-alt text--small"></i>
                                        </a>
                                    </span>
                                    @lang('page. Please use the same Google Cloud Project you used in the previous steps. Click the Create Service Account button')
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Then a new popup will appear, just enter your service account name then a service account will auto-generate. Just copy the service id(email id) and click the create and continue button')
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Now a new window will be visible, just click on the select a roll drop-down button. Select 2 roles Pub/Sub Admin and Monitoring Viewer. Click on the continue button, and then the done button')
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Find the newly created account in the list and the actions click manage keys. Create a new JSON key and save it locally on your computer. And Upload it to the admin panel')
                                </li>
                            </ul>
                        </div>
                        <h6 class="mb-2 ">@lang('3. Grant Permissions in the Google Play Console')</h6>
                        <div class="ms-3">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i> @lang('Go to the')
                                    <a href="https://play.google.com/console/u/0/developers/users-and-permissions" target="_blank" class="text--primary">@lang('Users and Permissions') <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    @lang('page in the Google Play Console and click Invite new users')
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang("Enter the email of the service account you've created in section 2 of this guide and make sure you select your app properly")
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    @lang('Check on below mentioned permission and click on apply button')
                                    <ul class="ms-4 mt-2">
                                        <li class="my-1"> <i class="las la-check-circle text--primary"></i> @lang('View app information (read only)')</li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> @lang('View financial data')</li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> @lang('Manage orders subscriptions')</li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> @lang('Manage store presence')</li>
                                    </ul>
                                    <p class="fw-bold my-1">
                                        @lang('Note: It takes at least 24 hours for changes to take effect but there is a hacking way. Just check this ')
                                        <a href="https://stackoverflow.com/questions/43536904/google-play-developer-api-the-current-user-has-insufficient-permissions-to-pe/60691844#60691844" target="_blank" class="text--primary">@lang('link') <i class="fas fa-external-link-alt text--small"></i>
                                        </a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="appPurchaseModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Google Pay Credential')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.setting.app.purchase.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="mt-2">@lang('File')</label>
                            <input type="file" class="form-control" name="file" accept=".json" required>
                            <small class="mt-3 text-muted">@lang('Supported Files: .json')</small>
                        </div>
                    </div>
                    @can('admin.setting.app.purchase.submit')
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Update')</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')

    @can('admin.setting.app.purchase.submit')
    <button class="btn btn-outline--primary updateBtn btn-sm" data-bs-toggle="modal" data-bs-target="#appPurchaseModal" type="button"><i class="las la-upload"></i>@lang('Update File')</button>
    @endcan

    @can('admin.setting.app.purchase.file.download')
        <a href="{{ route('admin.setting.app.purchase.file.download') }}" class="btn btn-outline--info updateBtn btn-sm  @if (!$fileExists) disabled @endif" @disabled(!$fileExists)>
            <i class="las la-download"></i>@lang('Download File')
        </a>
    @endcan
@endpush
