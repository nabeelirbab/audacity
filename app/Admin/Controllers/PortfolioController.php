<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Enums\CopierType;
use App\Models\Portfolio;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class PortfolioController extends AdminController
{
    protected function title() {
        return trans('admin.portfolios');
    }

    protected function grid() {
        return new Grid(new Portfolio(), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID')->sortable();
            $grid->column('title', 'Title');
            $grid->initial_deposit('Deposit');
            $grid->last_balance('Last Balance');
            $grid->deposited_at('Deposited At');
            $grid->accounts('Accounts/Backtests')->pluck('title')->badge('blue');

            $grid->profit('P/L');
            $grid->drawdown('DD%');
            $grid->countOrders('#Orders');

            $grid->created_at('Created')->sortable();

            $grid->is_public()->switch();

            $grid->actions(function ($actions) {
                $actions->disableView();
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return new Form(new Portfolio(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->display('id', 'ID');
            $form->text('title', 'Title')->required();
            $form->decimal('initial_deposit','Initial Deposit')->required();
            $form->decimal('last_balance','Last Balance')->required();
            $form->datetime('deposited_at','Initial Date')->required()->options(['locale'=>'en']);

            $accounts = Account
                ::whereManagerId(Admin::user()->id)
                ->whereIn('copier_type', [CopierType::SENDER, CopierType::STRATEGY])
                ->pluck('title', 'id');

            $form->multipleSelect('accounts', 'Accounts/Backtests')
                ->options($accounts)
                ->required()
                ->customFormat(function ($v) {
                    return array_column($v, 'id');
                });

                //todo::fix select all
                //->allowSelectAll();

            $form->switch('is_public', 'Public?')->default(1);
            $form->textarea('description', 'Description');

            $form->display('created_at', 'Started At');
            $form->display('created_at', 'Expired At');

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });

        });
    }
}
