<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Product;

use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class CampaignController extends AdminController
{
    protected function title() {
        return trans('defender.campagn');
    }

    protected function detail($id)
    {
        $show = new Show(Campaign::findOrFail($id));

        $show->slug('Slug');
        $show->title('Title');
        $show->expired_at('Expired at');

        $show->users('Users', function ($user) {
            $user->disableCreation();
            $user->disableActions();
            //$member->resource('/cmembers');

//            $user->refence_source('Ref');
            $user->name('Name');
            $user->email('Email');
            $user->created_at('Joined At');
            //$member->license_key('License');
        });

        return $show;
    }

    protected function grid()
    {
        return new Grid(new Campaign(), function (Grid $grid) {

            //$grid->disableCreation();
            //$grid->disableRowSelector();
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID')->sortable();
            $grid->slug('Slug');
            $grid->column('title','Title');
            $grid->products('Products')->pluck('title')->badge('blue');
            $grid->max_live_accounts('Max Live');
            $grid->max_demo_accounts('Max Demo');
            $grid->single_pc('Single PC')->switch()->sortable();
            $grid->auto_confirm_new_accounts('Auto Confirm')->switch();

            //$grid->column('Opts');
            //$grid->column('Files');

            $grid->created_at('Created');
            $grid->expired_at('Expired in')->display(function ($expired_at) {
                if(Carbon::Now() > $expired_at)
                    return "<span class='label label-warning' >Expired</span>";
                return Carbon::parse($expired_at)->diffForHumans();
            });

            $grid->users('Users')->display(function ($users) {
                $count = count($users);
                return "<span class='label label-info'>{$count}</span>";
            });

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
            });

            $grid->actions(function ($actions) {
                //$actions->disableView();
                //$actions->disableDelete();
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
        return new Form(new Campaign(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->display('id', 'ID');

            if ($form->isEditing()) {
                $form->display('slug', 'Slug');
            } else {
                $form->text('slug', 'Slug');
            }

            $form->text('title', 'Title')->required();
            $products = Product::whereManagerId(Admin::user()->id)->pluck('title', 'id');
            $form->multipleSelect('products', 'Products')
                ->options($products)
                ->required()
                ->customFormat(function ($v) {
                    return array_column($v, 'id');
                });
                //->allowSelectAll();

            $form->textarea('description', 'Description');
            $form->date('expired_at', 'Expired')->required();
            $form->number('max_live_accounts', 'Max Live')->default(1);
            $form->number('max_demo_accounts', 'Max Demo')->default(1);
            $states = [
                '1' => ['text' => 'Yes'],
                '0' => ['text' => 'No'],
            ];
            $form->switch('single_pc', 'Single PC?')->default(1);
            $form->switch('auto_confirm_new_accounts', 'Auto Confirm New Accounts?')->default(1);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
