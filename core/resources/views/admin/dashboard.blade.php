@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Current Fund')</h5>

                    <div class="row g-0">
                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('Users Wallet')</small>
                                <h6>{{ gs('cur_sym') . showAmount($funds['balance'], currencyFormat: false) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100 ">
                                <small class="text-muted">@lang('On Account of FDR')</small>
                                <h6>{{ gs('cur_sym') . showAmount($funds['dps'], currencyFormat: false) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('On Account of DPS')</small>
                                <h6>{{ gs('cur_sym') . showAmount($funds['dps'], currencyFormat: false) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100 ">
                                <small class="text-muted">@lang('Total In Fund')</small>
                                <h5>{{ gs('cur_sym') . showAmount(array_sum($funds), currencyFormat: false) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class="card-title">@lang('Deposit & Withdraw Report')</h5>

                        <div id="dwDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>

                    <div id="dwChartArea"> </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class="card-title">@lang('Transactions Report')</h5>

                        <div id="trxDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>

                    <div id="transactionChartArea"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Pending')</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Deposit Requests')</span>
                            <a href="@can('admin.deposit.pending'){{ route('admin.deposit.pending') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_deposit_pending']) bg--warning text--black @else bg--success text-white @endif">
                                    {{ $widget['total_deposit_pending'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Withdrawal Requests')</span>

                            <a href="@can('admin.withdraw.data.pending'){{ route('admin.withdraw.data.pending') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_withdraw_pending']) bg-warning  text-black @else bg--success text-white @endif">
                                    {{ $widget['total_withdraw_pending'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Loan Applications')</span>

                            <a href="@can('admin.loan.pending'){{ route('admin.loan.pending') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_pending_loan']) bg--danger text-white @else bg--success text-white @endif">
                                    {{ $widget['total_pending_loan'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Supprt Tickets')</span>

                            <a href="@can('admin.ticket.pending'){{ route('admin.ticket.pending') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['pending_tickets']) bg--10 text-white @else bg--success text-white @endif">
                                    {{ $widget['pending_tickets'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('KYC Verifications')</span>

                            <a href="@can('admin.users.kyc.pending'){{ route('admin.users.kyc.pending') }}@else # @endif">
                                <span class="badge @if ($widget['kyc_pending_users']) bg--5 text-white fw-bold @else bg--1 text--white @endif">
                                    {{ $widget['kyc_pending_users'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Money Transfers')</span>
                            <a href="@can('admin.transfers.pending'){{ route('admin.transfers.pending') }}@else # @endif">
                                <span class="badge fw-bold @if ($widget['pending_transfers']) bg--6 text--black  @else bg--success text-white @endif">
                                    {{ $widget['pending_transfers'] }}
                                </span>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title ">@lang('Installment Due')</h5>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('FDR')</span>


                            <a href="@can('admin.fdr.due'){{ route('admin.fdr.due') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_due_fdr']) bg--warning text--black @else bg--success text-white @endif">
                                    {{ $widget['total_due_fdr'] }}
                                </span>
                            </a>

                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('DPS')</span>

                            <a href="@can('admin.dps.due'){{ route('admin.dps.due') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_due_dps']) bg-warning text--black @else bg--success text-white @endif">
                                    {{ $widget['total_due_dps'] }}
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Loan')</span>

                            <a href="@can('admin.loan.due'){{ route('admin.loan.due') }}@else # @endif">
                                <span class="fw-bold badge @if ($widget['total_due_loan']) bg--danger @else bg--success text-white @endif">
                                    {{ $widget['total_due_loan'] }}
                                </span>
                            </a>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">

            <div class="card">

                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Ongoing')</h5>
                    <div class="row gy-3 ongoing-widget">

                        <div class="col-12">
                            <x-widget value="{{ $widget['total_running_fdr'] }}" title="Running FDR" :box_shadow=false style="2" bg="white" color="amber" icon="las la-store" link="admin.fdr.running" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-12">
                            <x-widget value="{{ $widget['total_running_dps'] }}" title="Running DPS" :box_shadow=false style="2" bg="white" color="7" icon="las la-coins" link="admin.dps.running" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-12">
                            <x-widget value="{{ $widget['total_matured_dps'] }}" title="Matured DPS" :box_shadow=false style="2" bg="white" color="warning" icon="las la-coins" link="admin.dps.matured" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-12">
                            <x-widget value="{{ $widget['total_running_loan'] }}" title="Running Loan" :box_shadow=false style="2" bg="white" color="indigo" icon="las la-hand-holding-usd" link="admin.loan.running" icon_style="solid" overlay_icon=0 />
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-xxl-6 col-xl-7 col-lg-8">
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Accounts')</h5>

                    <div class="row g-3 account-widget">

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['total_users'] }}" title="Total Registered" :box_shadow=false style="2" bg="white" color="info" icon="la la-users" link="admin.users.all" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['profile_completed'] }}" title="Profile Completed"  :box_shadow=false style="2" bg="white" color="success" icon="la la-user-check" link="admin.users.profile.completed" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['active_users'] }}" title="Active"  :box_shadow=false style="2" bg="white" color="green" icon="la la-user-check" link="admin.users.active" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['banned_users'] }}" title="Banned"  :box_shadow=false style="2" bg="white" color="danger" icon="la la-user-slash" link="admin.users.banned" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['email_unverified_users'] }}" title="Email Unverified"  :box_shadow=false style="2" bg="white" color="5" icon="la la-envelope" link="admin.users.email.unverified" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['mobile_unverified_users'] }}" title="Mobile Unverified"  :box_shadow=false style="2" bg="white" color="2" icon="la la-mobile" link="admin.users.mobile.unverified" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['kyc_unverified_users'] }}" title="KYC Unverified"  :box_shadow=false style="2" bg="white" color="3" icon="la la-user-slash" link="admin.users.mobile.unverified" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['kyc_pending_users'] }}" title="KYC Pending"  :box_shadow=false style="2" bg="white" color="warning" icon="la la-user" link="admin.users.mobile.unverified" icon_style="solid" overlay_icon=0 />
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row gy-4 mt-3">
        <div class="col-xl-4 col-lg-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->guard('admin')->id() == 1)
        @include('admin.partials.cron_modal')
    @endif
@endsection

@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        .card .list-group-item {
            padding: .57rem 1rem;
        }
        .account-widget .widget-two, .ongoing-widget .widget-two{
            border: 1px solid #eee !important;
        }
        .list-group-item {
            border-color: #eee !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}" />
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let dwChart = barChart(
            document.querySelector("#dwChartArea"),
            @json(__(gs('cur_text'))),
            [{
                    name: 'Deposited',
                    data: []
                },
                {
                    name: 'Withdrawn',
                    data: []
                }
            ],
            [],
        );

        let trxChart = lineChart(
            document.querySelector("#transactionChartArea"),
            [{
                    name: "Plus Transactions",
                    data: []
                },
                {
                    name: "Minus Transactions",
                    data: []
                }
            ],
            []
        );


        const depositWithdrawChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.chart.deposit.withdraw'));

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        dwChart.updateSeries(data.data);
                        dwChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        const transactionChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.chart.transaction'));


            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {


                        trxChart.updateSeries(data.data);
                        trxChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }



        $('#dwDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dwDatePicker span', start, end));
        $('#trxDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#trxDatePicker span', start, end));

        changeDatePickerText('#dwDatePicker span', start, end);
        changeDatePickerText('#trxDatePicker span', start, end);

        depositWithdrawChart(start, end);
        transactionChart(start, end);

        $('#dwDatePicker').on('apply.daterangepicker', (event, picker) => depositWithdrawChart(picker.startDate, picker.endDate));
        $('#trxDatePicker').on('apply.daterangepicker', (event, picker) => transactionChart(picker.startDate, picker.endDate));

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chartData['user_browser_counter']->keys()),
            @json(@$chartData['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chartData['user_os_counter']->keys()),
            @json(@$chartData['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chartData['user_country_counter']->keys()),
            @json(@$chartData['user_country_counter']->flatten())
        );
    </script>
@endpush
