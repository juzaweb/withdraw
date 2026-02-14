<?php

namespace Juzaweb\Modules\Withdraw\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Referral\Http\DataTables\WithdrawMethodsDataTable;
use Juzaweb\Modules\Referral\Http\Requests\WithdrawMethodActionsRequest;
use Juzaweb\Modules\Referral\Http\Requests\WithdrawMethodRequest;
use Juzaweb\Modules\Withdraw\Models\WithdrawMethod;

class WithdrawMethodController extends AdminController
{
    public function index(WithdrawMethodsDataTable $dataTable)
    {
        Breadcrumb::add(__('Withdraw Methods'));

        $createUrl = action([static::class, 'create']);

        return $dataTable->render(
            'withdraw::withdraw-method.index',
            compact('createUrl')
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Withdraw Methods'), admin_url('withdrawmethods'));

        Breadcrumb::add(__('Create Withdraw Method'));

        $backUrl = action([static::class, 'index']);

        return view(
            'withdraw::withdraw-method.form',
            [
                'model' => new WithdrawMethod(),
                'action' => action([static::class, 'store']),
                'backUrl' => $backUrl,
            ]
        );
    }

    public function edit(string $id)
    {
        Breadcrumb::add(__('Withdraw Methods'), admin_url('withdrawmethods'));

        Breadcrumb::add(__('Create Withdraw Methods'));

        $model = WithdrawMethod::findOrFail($id);
        $backUrl = action([static::class, 'index']);

        return view(
            'withdraw::withdraw-method.form',
            [
                'action' => action([static::class, 'update'], [$id]),
                'model' => $model,
                'backUrl' => $backUrl,
            ]
        );
    }

    public function store(WithdrawMethodRequest $request)
    {
        $model = DB::transaction(
            function () use ($request) {
                $data = $request->validated();
                $data['fields'] = collect($data['fields'])->values()->all();

                return WithdrawMethod::create($data);
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('WithdrawMethod :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(WithdrawMethodRequest $request, string $id)
    {
        $model = WithdrawMethod::findOrFail($id);

        $model = DB::transaction(
            function () use ($request, $model) {
                $data = $request->validated();
                $data['fields'] = collect($data['fields'])->values()->all();

                $model->update($data);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('WithdrawMethod :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function bulk(WithdrawMethodActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = WithdrawMethod::whereIn('id', $ids)->get();

        foreach ($models as $model) {
            if ($action === 'activate') {
                $model->update(['active' => true]);
            }

            if ($action === 'deactivate') {
                $model->update(['active' => false]);
            }

            if ($action === 'delete') {
                $model->delete();
            }
        }

        return $this->success([
            'message' => __('Bulk action performed successfully'),
        ]);
    }
}
