<?php

use Juzaweb\Modules\Withdraw\Http\Controllers\WithdrawController;

Route::group(
    [
        'middleware' => ['auth', 'verified'],
    ],
    function () {
        Route::post('withdraw', [WithdrawController::class, 'store'])
            ->name('withdraw.store');
    }
);
