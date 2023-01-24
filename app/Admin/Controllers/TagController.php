<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class TagController extends AdminController
{
    protected function grid()
    {

        return new Grid( new Tag(), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::id());

            $grid->column('title')->editable();
            $grid->color()->display(function($color) {
                return "<span class='label' style='background-color:{$color}'>&nbsp;&nbsp;</span>";
            });

            $grid->enabled()->switch();

            $grid->disableFilterButton();
            $grid->disableRefreshButton();
            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return new Form( new Tag(), function (Form $form) {
            $form->hidden('manager_id')->value(Admin::id());

            $form->text('title')->required();
            $form->color('color')->required();
            $form->switch('enabled')->default(1);

            $form->disableViewButton();
        });
    }
}