<?php

namespace Juzaweb\Modules\Withdraw\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;

class WithdrawMethod extends Model
{
    use HasAPI,  UsedInFrontend;

    protected $table = 'withdraw_methods';

    protected $fillable = [
        'name',
        'description',
        'fields',
        'min_amount',
    ];

    protected $casts = [
        'fields' => 'array',
        'min_amount' => 'decimal:2',
    ];
}
