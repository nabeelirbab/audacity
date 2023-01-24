<?php

namespace App\Admin\Controllers;

use App\Models\Licensing;
use App\Models\User4Defender;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

class User4DefenderController extends UserController
{

    protected function details($id)
    {
        $show = new Show(Licensing::findOrFail($id));

        $show->id('ID');
        $show->field('user.name','Name');
        $show->field('user.email','Email');
        $show->field('user.created_at','Created');
        $show->field('user.updated_at','Updated');

        $show->relation('accounts', 'Accounts', function (User4Defender $user) {

            return new Grid($user->accounts()->getQuery()->with('broker_server'), function (Grid $grid) {

                $grid->disableBatchActions();
                $grid->disableCreateButton();
                $grid->disableRowSelector();

                $grid->account_number('Account');
                $grid->name('Name');
                $grid->column('broker_server.name','Broker');
                $grid->created_at('Created');
                $grid->updated_at('Created');
            });

        });

        return $show;
    }

    protected function grid()
    {
        return new Grid(new User4Defender(), function (Grid $grid) {
            $grid->model()
                ->with('licensing')
                ->with('campaign')
                ->where('manager_id', Admin::user()->id);

            $grid->id('ID');
            $grid->email('Email');
            $grid->name('Name');
            $grid->note('Note');

            $grid->created_at()->sortable();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->like('email');
                $filter->like('name');
                $filter->like('note');
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
        return new Form(new User4Defender(), function (Form $form) {

            $form->display('id', 'ID');
            $form->hidden('manager_id')->value(Admin::user()->id);
            $form->hidden('username');
            $form->hidden('password');

            $form->email('email', 'Email')
                ->required()
                ->creationRules(["unique:admin_users"])
                ->updateRules(["unique:admin_users,email,{{id}}"]);

            $form->text('name', 'User Name')->required();
            $form->textarea('note', 'Note');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });

            $form->saving(function (Form $form) {
                $form->password = bcrypt($form->email);
                $form->username = $form->email;
            });
        });
    }

}