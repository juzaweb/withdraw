<?php

namespace Juzaweb\Modules\Withdraw\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\HasCodeWithMonth;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\Withdraw\Enums\WithdrawStatus;

class Withdraw extends Model
{
    use HasAPI,
        HasUuids,
        HasCodeWithMonth,
        UsedInFrontend;

    protected $table = 'withdraws';

    protected $fillable = [
        'withdrawable_id',
        'withdrawable_type',
        'method',
        'amount',
        'type',
        'status',
        'note',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => WithdrawStatus::class,
        'meta' => 'array',
    ];

    public function withdrawable(): MorphTo
    {
        return $this->morphTo();
    }
}
