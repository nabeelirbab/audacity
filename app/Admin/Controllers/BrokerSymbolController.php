<?php

namespace App\Admin\Controllers;

use App\Models\BrokerSymbol;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class BrokerSymbolController extends AdminController
{

    protected function title() {
        return trans('admin.broker_symbools');
    }

    protected function grid()
    {
        return new Grid( new BrokerSymbol(), function (Grid $grid) {

            $grid->name('Name')->editable();
            $grid->spread('Spread');

            $states = [
                '1' => ['text' => 'Yes'],
                '0' => ['text' => 'No'],
            ];
            $grid->enabled('Enabled')->switch($states)->sortable();

            $grid->filter(function ($filter) {
                $filter->like('name');
                $filter->disableIdFilter();
            });

            $grid->actions(function ($actions) {
                $actions->disableView();
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
        return new Form( new BrokerSymbol(), function (Form $form) {
            $form->display('id', 'ID');

            $form->text('name', 'Name')->required();
            $form->text('spread', 'Spread');
            $form->switch('enabled', 'Enabled?')->default(1);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
