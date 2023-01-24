<?php

namespace App\Admin\Views;

use App\Admin\Extensions\Tools\ClearCopierErrorsButton;
use App\Models\Account;
use App\Models\Order;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class CopierErrorsView extends LazyRenderable
{

    public function __construct() {
    }

    public function grid(): Grid
    {
        return new Grid( new Order(), function (Grid $grid) {

            $grid->model()
                ->where('ticket', '<', 0)
                ->whereHas('account', function ($q) {
                    $q->whereManagerId(Admin::id());
                })
                //->where('last_error_code', '<', 6000)
                ->orderBy('time_created', 'DESC');

            $grid->column('time_created','Time')->sortable();
            $grid->column('account_number','Account');
            $grid->column('last_error','Last Error');

            $grid->filter(function ($filter) {
                $filter->between('time_created', 'Time')->datetime();
                $filter->equal('account_number', 'Account')
                    ->select(Account::whereManagerId(Admin::user()->id)->pluck('account_number','account_number'));
                $filter->like('last_error', 'Last Error');
                $filter->disableIdFilter();
                $filter->panel();
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->append(new ClearCopierErrorsButton(___('clear')));
            });

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->paginate(10);

            $grid->quickSearch(['account_number']);
        });
    }

}
