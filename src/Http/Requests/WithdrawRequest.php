<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Withdraw\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\Rules\ModelExists;
use Juzaweb\Modules\Withdraw\Enums\WithdrawStatus;
use Juzaweb\Modules\Withdraw\Models\Withdraw;
use Juzaweb\Modules\Withdraw\Models\WithdrawMethod;
use Juzaweb\Modules\Withdraw\Rules\CheckUserBalance;

class WithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        $method = WithdrawMethod::find($this->input('method'));

        $rules = [
			'method' => ['required', 'bail', new ModelExists(WithdrawMethod::class, 'id')],
			'amount' => ['required', 'bail'],
            'type' => ['required', 'string', 'in:refferal,short-link'],
		];

        if ($method) {
            $rules['amount'][] = Rule::numeric()->min($method->min_amount);
            $rules['amount'][] = new CheckUserBalance($method->min_amount);

            foreach ($method->fields ?? [] as $field) {
                $rules['fields.' . $field['name']] = ['required', 'bail', 'string'];
            }
        }

        return $rules;
    }

    public function withdraw()
    {
        $method = WithdrawMethod::find($this->input('method'));
        $user = $this->user();

        $model = new Withdraw();
        $model->withdrawable()->associate($user);
        $model->fill($this->only(['amount', 'type']));
        $model->method = $method->name;
        $model->meta = $this->input('fields', []);
        $model->status = WithdrawStatus::PENDING;
        $model->save();

        $user->decrement('amount', $model->amount);

        return $model;
    }
}
