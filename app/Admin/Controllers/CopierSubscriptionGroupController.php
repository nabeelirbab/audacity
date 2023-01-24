<?php

namespace App\Admin\Controllers;

use App\Models\CopierSubscription;
use App\Models\CopierSubscriptionGroup;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class CopierSubscriptionGroupController extends AdminController
{
    protected function title() {
        return trans('admin.copier_groups');
    }

    protected function grid()
    {
        return new Grid( new CopierSubscriptionGroup(), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID');
            $grid->title('Title');
            $grid->subscriptions('Subscriptions')->pluck('title')->label();

            $grid->created_at()->sortable();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->like('title');
                $filter->disableIdFilter();
            });

            $grid->tools(function ($tools) {
                $tools->disableRefreshButton();
            });

            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        });
    }

    protected function form()
    {
        return new Form( new CopierSubscriptionGroup(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->text('title', 'Title')->required();

            $subscriptions = CopierSubscription::whereManagerId(Admin::user()->id)->pluck('title', 'id');
            $form->multipleSelect('subscriptions', 'Subscriptions')->options($subscriptions)->required()
            ->customFormat(function ($v) {
                return array_column($v, 'id');
            });
            //->allowSelectAll();

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
