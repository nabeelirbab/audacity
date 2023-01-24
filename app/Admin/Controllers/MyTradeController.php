<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\OrderCloseBatchAction;
use App\Enums\OrderType;
use App\Models\Account;
use App\Models\Order;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Request;

class MyTradeController extends AdminController
{
    protected $translation = 'my-trades';

    protected function grid()
    {
        return new Grid(new Order(), function (Grid $grid) {
            $accounts = Account::where([['user_id', Admin::user()->id]])->pluck('account_number', 'account_number');

            $grid->model()
                ->where('magic', '!=', 0)
                ->whereIn('account_number', $accounts->keys())
                ->whereIn('type', [OrderType::BUY, OrderType::SELL])
                ->orderBy('status', 'ASC')->orderBy('time_close', 'DESC');

            $grid->column('status')->enum();
            $grid->ticket('Ticket');
            $grid->symbol('Symbol');
            $grid->column('type')->enumColored();

            $grid->lots('Lots');
            $grid->price('Price');
            $grid->price_close('Closed');
            $grid->time_open('Time');
            $grid->time_close('Closed');
            $grid->pl('P/L');
            $grid->pips('Pips');
            $grid->comment('Com');

            $grid->filter(function (Grid\Filter $filter) use ($accounts) {
                $filter->panel();
                $filter->expand();
                $filter->disableIdFilter();

                if (count($accounts) > 0) {
                    $def = $accounts->keys()->first();

                    $filter->equal('account_number', 'Account')->select($accounts)->default($def);
                }
            });

            $grid->rows(function ($rows) {

                $rows->map(function ($row) {
                    if ($row->pl >= 0) {
                        $row->style('color:green');
                    } else {
                        $row->style('color:red');
                    }
                });

            });

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableViewButton();

            $grid->tools(function ($tools) use($accounts) {

                $acc = Request::get('account_number');

                if (empty($acc) && count($accounts) > 0)
                    $acc = $accounts->keys()->first();

                $tools->batch(function (Grid\Tools\BatchActions $batch) use($acc) {
                    $batch->add(new OrderCloseBatchAction($acc));
                });
            });

        });
    }
}
