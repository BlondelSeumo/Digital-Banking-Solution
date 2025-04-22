<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete', 'autoLogout'])->group(function () {

        Route::namespace('User')->group(function () {
            // Actions
            Route::controller('OTPController')->group(function () {
                Route::get('verify/otp', 'verifyOtp')->name('otp.verify');
                Route::post('check/otp/{id}', 'submitOTP')->name('otp.submit');
                Route::post('resend/otp/{id}', 'resendOtp')->name('otp.resend');
            });

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history')->middleware('checkModule:deposit');
                Route::get('deposit-details/{trxNumber}', 'details')->name('deposit.details')->middleware('checkModule:deposit');

                Route::get('transactions', 'transactions')->name('transaction.history');
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');

                Route::get('referees', 'referredUsers')->name('referral.users')->middleware('checkModule:referral_system');
            });


            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });


            // Withdraw
            Route::middleware('checkModule:withdraw')->controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('', 'withdrawMoney');
                    Route::post('apply', 'apply')->name('.apply');
                    Route::get('money', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
                Route::get('details/{trxNumber}', 'details')->name('.details');
            });

            //================start user fdr route ================//
            Route::middleware('checkModule:fdr')->controller('FdrController')->name('fdr.')->prefix('fdr')->group(function () {
                Route::get('plans', 'plans')->name('plans');
                //FDR
                Route::get('details/{fdrNumber}', 'details')->name('details');
                Route::get('download/{fdrNumber}', 'fdrDownload')->name('download');
                Route::post('apply/{id}', 'apply')->name('apply')->middleware('kyc');
                Route::get('apply-preview', 'preview')->name('apply.preview')->middleware('kyc');
                Route::post('apply-confirm/{id}', 'confirm')->name('apply.confirm')->middleware('kyc');
                Route::get('list', 'list')->name('list');
                Route::post('close/{id}', 'close')->name('close')->middleware('kyc');
                Route::get('installments/{fdrNumber}', 'installments')->name('instalment.logs');
            });

            // ====================start user dps route ==================//
            Route::middleware('checkModule:dps')->controller('DpsController')->name('dps.')->group(function () {
                Route::get('dps-plans', 'plans')->name('plans');
                Route::get('dps', 'list')->name('list');
                Route::prefix('dps')->group(function () {
                    Route::post('apply/{id}', 'apply')->name('apply')->middleware('kyc');
                    Route::get('apply-preview', 'preview')->name('apply.preview')->middleware('kyc');
                    Route::post('apply-confirm/{id}', 'confirm')->name('apply.confirm')->middleware('kyc');
                    Route::get('details/{dpsNumber}', 'details')->name('details');
                    Route::post('withdraw/{id}', 'withdraw')->name('withdraw')->middleware('kyc');
                    Route::get('instalment/logs/{dps_number}', 'installments')->name('instalment.logs');
                });
            });

            // =================start user loan route ====================//
            Route::middleware('checkModule:loan')->controller('LoanController')->name('loan.')->group(function () {
                Route::get('loan-plans', 'plans')->name('plans');
                Route::get('loans', 'list')->name('list');
                Route::prefix('loan')->group(function () {
                    Route::get('details/{loanNumber}', 'details')->name('details');
                    Route::post('apply/{id}', 'applyLoan')->name('apply')->middleware('kyc');
                    Route::get('application-preview', 'loanPreview')->name('apply.form')->middleware('kyc');
                    Route::post('apply-confirm', 'confirm')->name('apply.confirm')->middleware('kyc');
                    Route::get('instalment/logs/{loan_number}', 'installments')->name('instalment.logs');
                });
            });

            // ====================start airtime route ==================//
            Route::middleware(['checkModule:airtime', 'kyc'])->controller('AirtimeController')->name('airtime.')->prefix('mobile-top-up')->group(function () {
                Route::get('', 'form')->name('form');
                Route::post('apply', 'apply')->name('apply');
                Route::get('top-up', 'topUp')->name('top.up');
                Route::get('operators-by-country/{id}', 'getOperatorByCountry')->name('country.operators');
            });

            // ======================= Beneficiary route=====================
            Route::controller('BeneficiaryController')->name('beneficiary.')->prefix('beneficiary')->group(function () {
                Route::get('own-bank/beneficiaries', 'ownBankBeneficiaries')->name('own')->middleware(['checkModule:own_bank']);
                Route::get('other-bank/beneficiaries', 'otherBankBeneficiaries')->name('other')->middleware(['checkModule:other_bank']);
                Route::post('own-bank/add', 'addOwnBeneficiary')->name('own.add')->middleware('checkModule:own_bank');
                Route::post('other-bank/add', 'addOtherBeneficiary')->name('other.add')->middleware('checkModule:other_bank');
                Route::get('other-bank/form-data/{bankId}', 'otherBankForm')->name('other.bank.form.data');
                Route::get('account-number/check', 'checkAccountNumber')->name('check.account');
                Route::get('check-duplicate', 'checkDuplicate')->name('check.duplicate');
                Route::get('details/{id}', 'details')->name('details');
            });

            // ===================Transfer ====================
            Route::name('transfer.')->prefix('transfer')->group(function () {
                Route::controller('UserController')->middleware(['checkModule:own_bank,other_bank,wire_transfer'])->group(function () {
                    Route::get('all', 'transferHistory')->name('history');
                    Route::get('preview/{trxNumber}', 'transferDetails')->name('details');
                });

                // ===================OWN Bank transfer ============
                Route::controller('OwnBankTransferController')->middleware('checkModule:own_bank')->prefix('own-bank')->name('own.bank.')->group(function () {
                    Route::get('', 'beneficiaries')->name('beneficiaries');
                    Route::post('request/{id}', 'transferRequest')->name('request')->middleware('kyc');
                    Route::get('confirm', 'confirm')->name('confirm')->middleware('kyc');
                });

                // ===================Other bank transfer ============
                Route::controller('OtherBankTransferController')->middleware('checkModule:other_bank')->prefix('other-bank')->name('other.bank.')->group(function () {
                    Route::get('', 'beneficiaries')->name('beneficiaries');
                    Route::post('request/{id}', 'transferRequest')->name('request')->middleware('kyc');
                    Route::get('confirm', 'confirm')->name('confirm')->middleware('kyc');
                });

                // =================== Wire Transfer ====================
                Route::controller('WireTransferController')->middleware(['checkModule:wire_transfer', 'kyc'])->prefix('wire-transfer')->name('wire.')->group(function () {
                    Route::get('', 'wireTransfer')->name('index');
                    Route::post('request', 'transferRequest')->name('request');
                    Route::get('confirm', 'confirm')->name('confirm');
                    Route::get('details/{id}', 'details')->name('details');
                });
            });
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->middleware('checkModule:deposit')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
