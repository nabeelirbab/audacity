<?php

use App\Admin\Extensions\Nav;
use App\Models\CopierSignalFollower;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Navbar;

Dcat\Admin\Color::extend('black', [
    'primary'        => '#42403b',
    'primary-darker' => '#42403b',
    'link'           => '#a8a9bb',
]);

Dcat\Admin\Color::extend('yellow', [
    'primary'        => '#FFC827',
    'primary-darker' => '#FFC827',
    'link'           => '#FFC827',
]);

Form::resolving(function($form) {
    $form->disableEditingCheck();
    $form->disableViewCheck();
    $form->disableCreatingCheck();
});

config(admin_setting()->toArray());

Grid::resolving(function(Grid $grid) {
    $grid->setActionClass(Grid\Displayers\Actions::class);
    $grid->paginate(config('admin.paginate-default'));
});

Admin::navbar(function (Navbar $navbar) {

    if(Admin::user() && Admin::user()->can('mng.can_see_past_due') ) {
        $count = CopierSignalFollower::where('is_past_due', 1)->count();
        $navbar->right('Past Due: '.$count );
    }

    app('impersonate')->isActive()
        && $navbar->right(new Nav\Link(__('admin.deimpersonate'), admin_url('user/deimpersonate')));

    Admin::user()
        && Admin::user()->id == Admin::domain()->manager_id
        && Admin::user()->can('mng.site_settings')
        && $navbar->right(new Nav\Link(FALSE, admin_url('site-settings'), __('admin.site_configuration'), 'feather icon-settings'));

    Admin::user()
        && Admin::user()->can('view.system_logs')
        && $navbar->right(new Nav\Link(FALSE, admin_url('/system-logs'), __('admin.system_logs'), 'feather icon-triangle'));

    Admin::user()
        && Admin::user()->can('view.auth_logs')
        && $navbar->right(new Nav\Link(FALSE, admin_url('/authentication-log'), __('admin.authentication_logs'), 'feather icon-clock'));

    Admin::user()
        && ( Admin::user()->can('mng.notifications') || Admin::user()->can('user.notifications'))
        && $navbar->right(new Nav\Notification(10, __('notification.notifications')));

        //todo:: remove on tamanager quit
    Admin::user()
        && Admin::user()->can('user.can_see_past_due')
        && CopierSignalFollower::hasPastDue()
        && admin_error('ATTENTION', 'One of your bot subscriptions is past due. Please call 703-757-8500 to prevent disconnection of your trading bot.');

});
