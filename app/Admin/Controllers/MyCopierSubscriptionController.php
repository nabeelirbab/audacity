<?php

namespace App\Admin\Controllers;

use App\Models\UserCopierSubscription;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class MyCopierSubscriptionController extends AdminController
{
    protected function title() {
        return trans('admin.my_subscriptions');
    }

    protected function grid()
    {
        return new Grid(new UserCopierSubscription(), function (Grid $grid) {
            $grid->model()->whereUserId(Admin::user()->id);

            $grid->id('ID')->sortable();

            $grid->subscription()->title('Title');

            $grid->expired_at()->display(function ($date) {
                return is_null($date) ? 'never' : $date;
            });

            $grid->disableActions();
            $grid->disableFilter();
            $grid->disableCreateButton();
        });
    }

}
