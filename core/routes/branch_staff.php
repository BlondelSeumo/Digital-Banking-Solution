<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->controller('LoginController')->middleware('branch.staff.guest')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout')->withoutMiddleware('branch.staff.guest');

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});


Route::get('banned-account', 'BranchStaffController@bannedAccount')->name('banned');

Route::middleware('branch.staff')->group(function () {
    Route::controller('BranchStaffController')->group(function () {
        Route::get('set-branch/{id}', 'setBranch')->name('branch.set');
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::get('staff-profile/{id}', 'staffProfile')->name('profile.other');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');
        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    Route::middleware('checkAccountOfficer')->group(function () {
        Route::post('deposit/{account}', 'DepositController@save')->name('deposit.save');
        Route::post('withdraw/{account}', 'WithdrawController@save')->name('withdraw.save');

        Route::controller('UserController')->name('account.')->prefix('accounts')->group(function () {
            Route::get('/', 'all')->name('all')->withoutMiddleware('checkAccountOfficer');
            Route::get('detail/{account}', 'detail')->name('detail')->withoutMiddleware('checkAccountOfficer');
            Route::get('find', 'find')->name('find');
            Route::get('open', 'open')->name('open')->middleware('checkModule:branch_create_user');
            Route::post('save', 'store')->name('save')->middleware('checkModule:branch_create_user');
            Route::get('edit/{account}', 'open')->name('edit');
            Route::post('update/{account}', 'update')->name('update');
        });
    });

    Route::get('branches', 'BranchStaffController@branches')->name('branches');

    Route::get('deposits', 'DepositController@deposits')->name('deposits');
    Route::get('withdrawals', 'WithdrawController@withdrawals')->name('withdrawals');
    Route::get('transactions', 'BranchStaffController@transactions')->name('transactions');
});
