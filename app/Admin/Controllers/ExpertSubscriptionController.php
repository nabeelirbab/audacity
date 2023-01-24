<?php

namespace App\Admin\Controllers;

use App\Models\Expert;
use App\Models\ExpertSubscription;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

class ExpertSubscriptionController extends AdminController
{
    protected function grid()
    {
        return new Grid(new ExpertSubscription(), function (Grid $grid) {
            $grid->model()->whereManagerId(Auth('admin')->user()->id);

            $grid->id('ID')->sortable();

            $grid->title('Title');

            $grid->experts('Experts')->pluck('name')->label();
            $grid->expire_days('Expire Days');
            $grid->count_templates('#Templates');

            $grid->created_at()->sortable();
            $grid->updated_at();

            $grid->disableFilter();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        });
    }

    protected function form()
    {
        return new Form(new ExpertSubscription(), function (Form $form) {
            $form->hidden('manager_id')->value(Auth::guard('admin')->user()->id);
            $form->text('title', 'Title');

            $form->multipleSelect('experts', 'Experts')->options(
                Expert::where([
                    ['manager_id', Auth::guard('admin')->user()->id],
                    ])->pluck('name', 'id')
            )
            ->customFormat(function ($v) {
                return array_column($v, 'id');
            });

            $form->number('count_templates', '#Templates')->default(1);
            $form->number('expire_days', '#Days to expire')->default(0);

            $form->switch('enabled', 'Enabled?')->default(1);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saved(function (Form $form) {
            });
        });
    }
}
