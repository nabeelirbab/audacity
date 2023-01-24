<?php

namespace App\Admin\Views;

use App\Models\Order;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class TradeHistoryView extends LazyRenderable
{
    public function __construct($accountNumber = null, $from = null, $to = null, array $payload = []) {

        $this->payload(['account_number' => $accountNumber]);
        $this->payload(['from' => $from]);
        $this->payload(['to' => $to]);
        parent::__construct($payload);
    }

    public function grid(): Grid
    {

        $accountNumber = $this->account_number;
        $from = $this->from;
        $to = $this->to;

        return new Grid(new Order(), function(Grid $grid ) use($accountNumber, $from, $to) {

            $model = $grid->model()
                ->whereAccountNumber($accountNumber)
                ->closed()
                ->orderBy('time_close', 'DESC');

            if(!is_null($from)) {
                $model->where('time_close', '>=', $from);
            }

            if(!is_null($to)) {
                $model->where('time_close', '<=', $to);
            }

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->disableRefreshButton();

            $grid->column('time_open','Open');
            $grid->column('type_str','Type');
            $grid->column('lots','Vol');
            $grid->column('symbol','Symbol');
            $grid->column('price','Price');
            $grid->column('stoploss','SL');
            $grid->column('takeprofit','TP');
            $grid->column('time_close','Close');
            $grid->column('price_close','Price');
            $grid->column('swap','Swap');
            $grid->column('pl','P/L');

            $grid->paginate(10);
        });
    }

}
