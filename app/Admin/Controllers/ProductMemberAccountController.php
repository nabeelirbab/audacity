<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Models\MemberProductAccount;
use App\Models\Product;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ProductMemberAccountController extends AdminController
{
    protected function title()
    {
        return trans('defender.member_accounts');
    }

    protected function detail($id)
    {
        $show = new Show(Account::findOrFail($id));

        $show->account_number('Account Number');
        $show->name('Account Name');
        $show->title('Title');
        $show->broker_server_name('Broker Server');
        $show->is_live('Live or Demo')->as(function ($val) {
            return $val == 1 ? 'Live' : 'Demo';
        });
        $show->currency('Currency');
        $show->api_version('API Version');
        $show->Company('Broker Company');

        $show->funds('Funds', function ($order) {
            $order->resource('/funds');

            $order->time_close('Time');
            $order->pl('Amount');
        });

        return $show;
    }

    protected function grid()
    {
        return new Grid (new MemberProductAccount(), function (Grid $grid) {
            $states = [
                '1' => ['text' => 'Yes'],
                '0' => ['text' => 'No'],
            ];


            $grid->model()
                ->with('member')->with('account')->with('user')->with('stat')
                ->whereHas('user', function ($query){
                    $query->whereManagerId(Admin::user()->id);
                })
                ->orderBy('confirmed');

            $grid->id('ID');
            $grid->column('user.name','User')->filter()->sortable()->ucfirst()->limit(10);
            $grid->column('user.email','Email')->sortable();
            $grid->column('member.license_key', 'License')->sortable()->copyable();
            $grid->column('product.title', 'Product')->sortable()->badge('blue');
            $grid->column('account.account_number','Account')->sortable();
            $grid->column('account.broker_server_name','Broker')->sortable();
            $grid->column('stat.balance','Balance')->sortable();

            $grid->column('product.version','Ver#');
            $grid->updated_at('Updated')->display(function ($updated_at) {
                return Carbon::parse($updated_at)->diffForHumans();
            });

            $grid->confirmed()->switch($states)->sortable();

            $grid->showColumnSelector();
            $grid->hideColumns(['product.version','updated_at']);

            $grid->filter(function ($filter) {
                $filter->scope('new', 'Added today')->whereDate('created_at', date('Y-m-d'));
                $filter->scope('Confirmed')->where('confirmed', '=', 1);
                $filter->scope('UnConfirmed')->where('confirmed', '=', 0);
                $filter->scope('Demo')->whereHas('account', function ($query) {
                    $query->where('is_live', '0');
                });
                $filter->scope('Live')->whereHas('account', function ($query) {
                    $query->where('is_live', '1');
                });

                //$filter->like('product.title', 'Product');
                $filter->equal('product.id', 'Product')
                    ->select(Product::whereManagerId(Admin::user()->id)->pluck('title', 'id'));

                $filter->like('user.name', 'Name');
                $filter->like('user.email', 'Email');
                $filter->like('account.account_number', 'Account');
                $filter->like('account.broker_server_name', 'Broker');

                $filter->group('stat.balance', function ($group) {
                    $group->gt('More');
                    $group->lt('Less');
                });
                $filter->disableIdFilter();
            });

            $grid->disableRefreshButton();
            $grid->disableActions();
            $grid->disableCreateButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(MemberProductAccount::class, function (Form $form) {
            $form->switch('confirmed', 'Confirmed?');
        });
    }

}
