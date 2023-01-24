<?php

namespace App\Admin\Extensions\Tools;

use App\Helpers\MT4Commands;
use App\Models\Order;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class OrderCloseBatchAction extends BatchAction
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

        $accountNumber = 0;
        $tickets_lots = [];
        foreach (Order::find($this->getKey()) as $order) {
            $accountNumber = $order->account_number;
            $tickets_lots[] = ['t' => (int)$order->ticket, 'l' => (double)$order->lots];
        }

        if($accountNumber != 0) {
            MT4Commands::wsSendOrderCloseSignal($accountNumber, $tickets_lots);
            return $this->response()
                ->success(trans('admin.orders_closed'))
                ->redirect($request->get('url'));
        } else {
            return $this->response()
                ->error(trans('admin.orders_not_closed'))
                ->redirect($request->get('url'));
        }
    }


}
