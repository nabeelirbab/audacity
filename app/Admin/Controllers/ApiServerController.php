<?php

namespace App\Admin\Controllers;

use App\Enums\ApiServerStatus;
use App\Models\ApiServer;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class ApiServerController extends AdminController
{

    protected function grid()
    {
        return new Grid(ApiServer::withCount('accounts'), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id;
            $grid->ip;
            $grid->column('title')->link(admin_url('apis-stat'), '');

            $grid->column('mem')->append('%');
            $grid->column('cpu')->append('%');

            $grid->column('api_server_status')->enumColored();

            $grid->column('accounts_count','#Accounts')->link(function() {
                return admin_route('accounts.index').'?api_server_ip='.$this->ip;
            },'');
            $grid->column('max_accounts');

            $grid->column('enabled')->switch();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
            });

            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return new Form(new ApiServer(), function (Form $form) {
            $form->display('id');

            $form->ip('ip');
            $form->text('title');

            $form->switch('enabled')->default(1);
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->number('max_accounts', 'Max Accounts');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
