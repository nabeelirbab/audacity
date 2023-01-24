<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\ClearNotificationButton;
use App\Admin\RowActions\MarkAsRead;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class MyNotificationController extends AdminController
{
    protected $translation = 'notification';

    protected function grid()
    {
        return new Grid( new DatabaseNotification(), function (Grid $grid) {

            $grid->selector( function(Grid\Tools\Selector $selector) {
                $selector->selectOne('status', [
                    'all' => 'All',
                    'unread' => 'Unread'
                ], function($query, $value) {

                    if($value == 'unread') {
                        $query->unread();
                        return;
                    }
                });

            });

            $grid->rows(function ($rows) {
                $rows->map(function (Grid\Row $row) {
                    if (is_null($row->read_at)) {
                        $row->style('font-wight:bold');
                    }
                });
            });

            $grid->actions(function(Actions $actions) {
                if($actions->row->unread()) {
                    $actions->prepend(new MarkAsRead());
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->append(new ClearNotificationButton('my-notifications',___('clear')));
            });

            $grid->model()->where('notifiable_id', Admin::user()->id)->orderBy('created_at', 'desc');

            $grid->column('created_at')->display(function ($created_at) {
                $t =  Carbon::parse($created_at)->diffForHumans();

                if(is_null($this->read_at)) {
                    return '<span class="label" style="background: #4863bf;">'.$t.'</span>';
                }

                return $t;
            });

            $grid->column('type')->display(function($type) {
                return ___(Str::remove('App\\Notifications\\', $type ));
            });
            $grid->column('data', __('message'))->display(function($data) {
                return isset($data['message'] ) ? $data['message'] : '';
            } );

            $grid->disableFilterButton();
            $grid->disableViewButton();
            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
        });
    }

    protected function form() {
        return new Form(new DatabaseNotification(), function($form) {

        });
    }
}