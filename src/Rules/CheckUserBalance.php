<?php

namespace Juzaweb\Modules\Withdraw\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckUserBalance implements ValidationRule
{
    public function __construct(protected ?float $minAmount = null)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();

        if (! $user) {
            $fail(__('User is not authenticated.'));
        }

        if ($value > $user->amount) {
            $fail(__('The :attribute exceeds your available balance.'));
        }

        if ($this->minAmount && $value < $this->minAmount) {
            $fail(__('The :attribute must be at least :min amount.', ['min' => $this->minAmount]));
        }
    }
}
