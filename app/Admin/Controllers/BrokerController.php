<?php

namespace App\Admin\Controllers;

use App\Models\BrokerServer;
use App\Models\BrokerServerWithAccounts;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class BrokerController extends AdminController
{

    protected function grid()
    {
        return new Grid(new BrokerServerWithAccounts(), function (Grid $grid) {
            $grid->id()->sortable();

            $grid->name()->sortable();
            $grid->gmt_offset();
            $grid->suffix();

            $grid->countAccounts()->label();

            $grid->created_at();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->like('name');
                $filter->disableIdFilter();
            });
            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return new Form( new BrokerServer(), function (Form $form) {
            $form->display('id', 'ID');

            if ($form->isEditing()) {
                $form->display('name');
            }

            if($form->isCreating())
                $form->file('srv_file_path')->required();
            else
                $form->file('srv_file_path');

            $form->number('gmt_offset')->default(0);
            $form->text('suffix')->help(___('help_suffix'));

            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(function ($form) {
                if (!is_null($form->srv_file_path)) {
                    $form->model()->name = str_replace('.srv', '', $form->srv_file_path->getClientOriginalName());
                    $form->model()->srv_file = $form->srv_file_path->get();
                    $form->model()->is_updated_or_new = 1;
                }
            });

            $form->disableViewButton();
        });
    }

}
