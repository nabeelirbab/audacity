<?php

namespace App\Admin\Controllers\Performance;

use App\Models\Account;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class MyAccountController extends AdminController
{
    protected $translation = 'my-accounts';

    protected function detail($id)
    {
        $show = new Show(Account::findOrFail($id));

        $show->account_number('Account Number');
        $show->name('Account Name');
        $show->broker_server_name('Broker Server');

        $show->password('Password');
        $show->created_at('Created');

        $show->disableDeleteButton();
        $show->disableEditButton();

        $show->relation('liveorders', 'Live Orders', function (Account $account) {

            return new Grid( $account->liveorders()->getQuery(), function (Grid $grid) use($account) {
                $grid->model()->orderBy('time_open', 'DESC');

                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableCreateButton();

                $grid->symbol('Symbol');
                $grid->type_str('Type')->sortable();
                $grid->lots('Lots');
                $grid->price('Price');
                $grid->stoploss('Stoploss');
                $grid->takeprofit('TakeProfit');
                $grid->time_open('Time');
                $grid->comment('Comment');
            });

        });

        $show->relation('closedorders','Closed Orders', function (Account $account) {
            return new Grid($account->closedorders()->getQuery(), function (Grid $grid) {
                $grid->model()->orderBy('time_close', 'DESC');
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableCreateButton();
                $grid->disableRowSelector();

                $grid->symbol('Symbol');
                $grid->type_str('Type')->sortable();
                $grid->lots('Lots');
                $grid->price('Price');
                $grid->stoploss('Stoploss');
                $grid->takeprofit('TakeProfit');
                $grid->time_open('Open');
                $grid->time_close('Close');
                $grid->price_close('Price');
                $grid->pl('P/L');
                $grid->pips('Pips');
                $grid->comment('Comment');
            });

        });

        return $show;
    }



    protected function grid()
    {
        return new Grid(Account::with('stat'), function (Grid $grid) {
            $grid->model()->where('user_id', Admin::user()->id);

            $grid->account_number('Login');
            $grid->broker_server_name('Server');
            $grid->column('stat.deposit','Deposit');
            $grid->column('stat.balance','Balance');
            $grid->column('stat.profit','P/L');

            $grid->column('details', '');

            $grid->rows(function($rows) use ($grid) {
                $rows->map(function(Grid\Row $row) use($grid){
                    $url = $grid->resource().'/'.$row->id;
                    $row->column('details', '<a href="'.$url.'">'.trans('trading-objectives.details').'</a>');
                });
            });

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableFilter();
        });
    }

}
