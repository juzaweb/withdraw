<?php

namespace Juzaweb\Modules\Withdraw\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Withdraw\Http\DataTables\WithdrawsDataTable;
use Juzaweb\Modules\Withdraw\Http\Requests\WithdrawActionsRequest;
use Juzaweb\Modules\Withdraw\Http\Requests\WithdrawRequest;
use Juzaweb\Modules\Withdraw\Models\Withdraw;

class WithdrawController extends AdminController
{
    public function index(WithdrawsDataTable $dataTable)
    {
        Breadcrumb::add(__('Withdraws'));

        return $dataTable->render(
            'withdraw::withdraw.index',
        );
    }

    public function store(WithdrawRequest $request)
    {
        $model = DB::transaction(fn() => $request->withdraw());

        return $this->success([
            'message' => __('Withdraw request created successfully'),
        ]);
    }

    public function bulk(WithdrawActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Withdraw::whereIn('id', $ids)->get();

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
