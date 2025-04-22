<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->group(function () {

    Route::controller('AppController')->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('language/{key}', 'getLanguage');
        Route::get('policies', 'policies');
        Route::get('faq', 'faq');
        Route::get('kyc-content', 'kycContent');
    });

    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });

        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });

        Route::middleware(['check.status'])->group(function () {

            Route::middleware('registration.complete')->group(function () {
                Route::controller('UserController')->group(function () {
                    Route::get('dashboard', 'dashboard');
                    Route::get('user-info', 'userInfo');

                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    //KYC
                    Route::get('kyc-form', 'kycForm');
                    Route::post('kyc-submit', 'kycSubmit');

                    //Report
                    Route::any('deposit/history', 'depositHistory');
                    Route::get('transactions', 'transactions');
                    Route::get('transfer/history', 'transferHistory');

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    Route::get('referees', 'referredUsers')->middleware('checkModule:referral_system');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');
                });

                // Withdraw
                Route::middleware('checkModule:withdraw')->controller('WithdrawController')->name('withdraw.')->group(function () {
                    Route::middleware('kyc')->group(function () {
                        Route::get('withdraw-method', 'withdrawMethod');
                        Route::post('apply', 'apply');
                        Route::get('withdraw/store/{id}', 'withdrawStore')->name('store');
                        Route::get('withdraw/preview/{trx}', 'withdrawPreview');
                        Route::post('withdraw-request/confirm/{trx}', 'withdrawSubmit');
                    });
                    Route::get('withdraw/history', 'withdrawLog');
                });

                Route::controller('OtpController')->group(function () {
                    Route::post('check/otp/{id}', 'submitOTP');
                    Route::post('resend/otp/{id}', 'resendOtp');
                });

                Route::middleware('checkModule:fdr')->controller('FdrController')->name('fdr.')->prefix('fdr')->group(function () {
                    Route::get('list', 'list');
                    Route::get('plans', 'plans');
                    Route::post('apply/{id}', 'apply');
                    Route::get('preview/{id}', 'preview')->name('apply.preview');;
                    Route::post('confirm/{id}', 'confirm');
                    Route::post('close/{id}', 'close')->name('close');
                    Route::get('instalment/logs/{fdr_number}', 'installments');
                });

                //====================start user dps route ==================//
                Route::middleware('checkModule:dps')->controller('DpsController')->name('dps.')->prefix('dps')->group(function () {
                    Route::get('plans', 'plans');
                    Route::post('apply/{id}', 'apply');
                    Route::get('preview/{id}', 'preview')->name('apply.preview');
                    Route::post('confirm/{id}', 'confirm');
                    Route::get('list', 'list');
                    Route::post('withdraw/{id}', 'withdraw');
                    Route::get('instalment/logs/{dps_number}', 'installments');
                });

                //=================start user loan route ====================///
                Route::middleware('checkModule:loan')->controller('LoanController')->prefix('loan')->group(function () {
                    Route::get('plans', 'plans');
                    Route::get('list', 'list');
                    Route::post('apply/{id}', 'applyLoan');
                    Route::post('confirm/{id}', 'loanConfirm');
                    Route::get('instalment/logs/{loan_number}', 'installments');
                });

                //=======================start user beneficiary route======================//
                Route::controller('BeneficiaryController')->prefix('beneficiary')->group(function () {
                    Route::get('own', 'ownBeneficiary')->middleware('checkModule:own_bank');
                    Route::post('own/{id?}', 'addOwnBeneficiary')->middleware('checkModule:own_bank');

                    Route::get('other', 'otherBeneficiary')->middleware('checkModule:other_bank');
                    Route::post('other/{id?}', 'addOtherBeneficiary')->middleware('checkModule:other_bank');
                    Route::get('/bank-data', 'bankFormData');
                    Route::get('/details/{id}', 'details');

                    Route::get('account-number/check', 'checkAccountNumber');
                });

                //===================start the user transfer route ====================//
                Route::controller('OwnTransferController')->prefix('own/transfer')->name('own.transfer.')->group(function () {
                    Route::middleware('checkModule:own_bank')->group(function () {
                        Route::post('request/{id}', 'transferRequest');
                        Route::get('confirm/{id}', 'confirm')->name('confirm');
                    });
                });
                Route::controller('OtherTransferController')->prefix('other/transfer')->name('other.transfer.')->group(function () {
                    Route::middleware('checkModule:other_bank')->group(function () {
                        Route::post('request/{id}', 'transferRequest');
                        Route::get('confirm/{id}', 'confirm')->name('confirm');
                    });
                });

                Route::controller('WireTransferController')->middleware('checkModule:wire_transfer')->prefix('wire-transfer')->group(function () {
                    Route::get('', 'wireTransfer');
                    Route::post('request', 'transferRequest');
                    Route::get('confirm/{id}', 'confirm')->name('transfer.wire.confirm');
                    Route::get('details/{id}', 'details');
                });

                Route::controller('AirtimeController')->middleware('checkModule:airtime')->prefix('airtime')->group(function () {
                    Route::get('countries', 'countries');
                    Route::get('operators/{countryId}', 'operators');
                    Route::post('apply', 'apply')->name('apply');
                    Route::get('top-up/{id}', 'topUp')->name('airtime.top.up');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods');
                    Route::post('deposit/insert', 'depositInsert');
                    Route::post('app/payment/confirm', 'appPaymentConfirm');
                });

                Route::controller('TicketController')->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });
            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
});
