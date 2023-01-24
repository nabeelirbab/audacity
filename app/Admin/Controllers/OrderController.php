<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\OrderCloseBatchAction;
use App\Models\Order;
use App\Enums\OrderStatus;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Request;

class OrderController extends AdminController
{
    protected function grid()
    {
        return new Grid(new Order(), function (Grid $grid) {

            $grid->model()->orderBy('status', 'ASC')->orderBy('time_close', 'DESC');

            $req_status = Request::get('status');

            if($req_status == OrderStatus::NOT_FILLED) {
                $grid->created_at()->sortable();
                $grid->last_error();
            } else {
                $grid->column('status')->enum()->sortable();

                $grid->symbol();
                $grid->type_str()->sortable();
                $grid->lots();
                $grid->price();
                $grid->stoploss();
                $grid->takeprofit();
                $grid->time_open();
                $grid->time_close();
                $grid->price_close();
                $grid->pl();
                $grid->comment();
            }

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableResetButton();
                $filter->panel();
                $filter->expand();
                $filter->like('account_number');
                $filter->equal('status', ___('status'))->select(OrderStatus::map());
                $filter->disableIdFilter();
            });

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->add(new OrderCloseBatchAction(Request::get('account_number')));
                });
            });

            $grid->disableCreateButton();
            $grid->disableActions();
            //$grid->disableBatchActions();
        });
    }
}
