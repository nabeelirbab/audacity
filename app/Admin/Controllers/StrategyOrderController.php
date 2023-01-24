<?php

namespace App\Admin\Controllers;

use App\Models\Order;

use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class StrategyOrderController extends AdminController
{
    protected function title()
    {
        return trans('admin.orders');
    }

    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->ticket('ticket');
        $show->updated_at('Updated');

        return $show;
    }

    protected function grid()
    {
        return new Grid(new Order(), function (Grid $grid) {
            $grid->model()->orderBy('time_close', 'DESC');
            $grid->symbol('Symbol');
            $grid->type_str('Type')->sortable();
            $grid->lots('Lots');
            $grid->price('Price');
            $grid->stoploss('Stoploss');
            $grid->takeprofit('TakeProfit');
            $grid->time_open('Time');
            $grid->time_close('Closed');
            $grid->price_close('PriceClose');
            $grid->pl('P/L');

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
            });

            $grid->tools(function ($tools) {
            });

            $grid->disableCreation();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        });
    }
}
