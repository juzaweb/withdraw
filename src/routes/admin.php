<?php

use Juzaweb\Modules\Withdraw\Http\Controllers\WithdrawController;
use Juzaweb\Modules\Withdraw\Http\Controllers\WithdrawMethodController;

Route::admin('withdraw-methods', WithdrawMethodController::class);
Route::admin('withdraws', WithdrawController::class)->only(['index', 'bulk']);
