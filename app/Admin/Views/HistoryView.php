<?php

namespace App\Admin\Views;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class HistoryView extends LazyRenderable
{

    public function __construct(array $payload = []) {

        parent::__construct($payload);
    }

    public function grid(): Grid
    {

        $model = Order
            ::with(['account'])
            ->whereHas('account', function($query) {
                $query->whereManagerId(Admin::user()->id)
            ->whereIn('type',[OrderType::BUY, OrderType::SELL])
            ->where('status', OrderStatus::CLOSED)
            ->orderBy('time_close', 'DESC');
        });


        $grid = new Grid($model);
        $grid->disableRefreshButton();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->paginate(10);

        $grid->column('ticket', __('mt4-account.ticket'));
        $grid->column('account.title', __('mt4-account.account'))->link(function() {
            return admin_route('accounts.index').'/'.$this->account['id'];
        },'');
        $grid->column('time_open', __('mt4-account.open_time'));
        $grid->column('symbol', __('mt4-account.symbol'));
        $grid->column('type_str', __('mt4-account.type'));
        $grid->column('lots', __('mt4-account.lots'));
        $grid->column('price', __('mt4-account.open_price'));
        $grid->column('stoploss', __('mt4-account.sl'));
        $grid->column('takeprofit', __('mt4-account.tp'));
        $grid->column('time_close', __('mt4-account.close_time'));
        $grid->column('price_close', __('mt4-account.close_price'));
        $grid->column('commission', __('mt4-account.com'));
        $grid->column('swap', __('mt4-account.swap'));
        $grid->column('pl', __('mt4-account.profit'));

        return $grid;
    }

}