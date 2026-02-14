<?php

namespace Juzaweb\Modules\Withdraw\Enums;

enum WithdrawStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function all(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::APPROVED->value => self::APPROVED->label(),
            self::REJECTED->value => self::REJECTED->label(),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::PENDING => '<span class="badge bg-secondary">' . $this->label() . '</span>',
            self::APPROVED => '<span class="badge bg-success">' . $this->label() . '</span>',
            self::REJECTED => '<span class="badge bg-danger">' . $this->label() . '</span>',
        };
    }
}
