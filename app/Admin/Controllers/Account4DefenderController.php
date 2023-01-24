<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Models\Member;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class Account4DefenderController extends AdminController
{

    protected function title()
    {
        return trans('admin.accounts');
    }

    protected function detail($id)
    {
        $show = new Show(Account::findOrFail($id));

        $show->account_number('Account Number');
        $show->broker_server_name('Broker Server');
        $show->is_live('Live or Demo')->as(function ($val) {
            return $val == 1 ? 'Live' : 'Demo';
        });
        $show->created_at('Created');
        $show->updated_at('Updated');

        return $show;
    }

    protected function grid()
    {
        return new Grid(new Account(), function (Grid $grid) {
            $grid->model()->with('stat');//->whereManagerId(Auth('admin')->user()->id);

            $grid->id('ID');

            $grid->account_number('Account');
            $grid->stat()->name('Name');
            //$grid->user()->name('User');
            $grid->broker_server_name('Broker')->sortable();

            $grid->stat()->balance('Balance');

            $grid->updated_at('Updated')->display(function ($updated_at) {
                return Carbon::parse($updated_at)->diffForHumans();
            });

            $def = null;
            $members = Member
                ::with('user')
                ->whereHas('user', static function ($user) {
                    $user->whereManagerId(Admin::user()->id);
                })->get();

            if (count($members) > 0) {
                $def = $members->values()->first()->user_id;
            }
            $arr = array();
            foreach ($members as $m) {
                $arr[$m->user_id] = $m->user->email.' - '. $m->user->name. ' - '.$m->license_key;
            }
            $grid->filter(function ($filter) use($arr, $def) {
                $filter->equal('user_id', 'Member')->select($arr)->default($def);
                $filter->disableIdFilter();
                $filter->expand();
            });

            $grid->disableCreateButton();
            $grid->disableRefreshButton();
        });
    }

}
