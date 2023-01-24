<?php

namespace App\Admin\Views;

use App\Models\Account;
use App\Models\Order;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class OpenTradesView extends LazyRenderable
{
    public function __construct($accountNumber = null, array $payload = []) {

        $this->payload(['account_number' => $accountNumber]);
        parent::__construct($payload);
    }

    public function grid(): Grid
    {

        $accountNumber = $this->account_number;

        return new Grid(new Order(), function(Grid $grid ) use($accountNumber) {
            $grid->model()->whereAccountNumber($accountNumber)->open()->orderBy('time_open', 'DESC');
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->disableRefreshButton();

            $grid->column('ticket','Ticket');
            $grid->column('time_open','Open');
            $grid->column('type_str','Type');
            $grid->column('lots','Vol');
            $grid->column('symbol','Symbol');
            $grid->column('price','Price');
            $grid->column('stoploss','SL');
            $grid->column('takeprofit','TP');
            $grid->column('swap','Swap');

            $grid->paginate(10);
        });
    }

}
