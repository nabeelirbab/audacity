<?php

namespace App\Admin\Extensions\Tools;

use App\Helpers\MT4Commands;
use App\Models\Order;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class OrderDeleteBatchAction extends BatchAction
{
    public function __construct($title = null)
    {
        parent::__construct($title);
    }

    protected function parameters()
    {
        return ['url' => request()->fullUrl()];
    }

    public function handle(Request $request)
    {

        Order::whereIn('ticket', $this->getKey())->delete();

        return $this->response()
            ->success(trans('admin.delete_success'))
            ->redirect($request->get('url'));
    }


}
