<?php

namespace App\Admin\Views;

use App\Models\Account;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class AccountsView extends LazyRenderable
{

    public function __construct(array $payload = []) {

        parent::__construct($payload);
    }

    public function grid(): Grid
    {
        $grid = new Grid(Account::with(['stat','user'])->whereManagerId(Admin::user()->id) );

        $grid->disableRefreshButton();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->paginate(10);

        $grid->column('account_number', __('admin.account_number'));

        $grid->column('title', __('admin.title'))->link(function() {
            return admin_route('accounts.index') . '/' . $this->id;
        },'');

        $grid->column('user.name', ___('user'))->link(function() {
            return admin_route('clients.index') . '/' . $this->user_id;
        },'')->filter();
        $grid->column('stat.gain_perc', __('growth'))->display(function($val) {
            if(!empty($val))
                return $val.'%';
            return $val;
        });
        $grid->column('stat.nof_working', __('accounts-view.open_trades'));

        $grid->column('stat.balance', __('mt4-account.balance'));
        $grid->column('stat.equity', __('mt4-account.equity'));
        $grid->column('stat.profit', __('profit'));
        $grid->column('stat.margin', __('margin'));
        $grid->column('analysis', ' ')
            ->icon('signal', ___('analysis'))
            ->link(function() {
                return admin_url('account-analysis').'/'.$this->account_number;
            },'');

        // $grid->rows(function($rows) {
        //     $rows->map(function(Grid\Row $row) {
        //         $url = admin_url('account-analysis/'.$row->account_number.'?ret=dashboard');
        //         $row->column('analysis', '<a href="'.$url.'" ><i class="fa fa-signal"></i></a>');
        //     });
        // });

        return $grid;
    }
}