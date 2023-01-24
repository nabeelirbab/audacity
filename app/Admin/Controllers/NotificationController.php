<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\ClearNotificationButton;
use App\Admin\RowActions\MarkAsRead;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class NotificationController extends AdminController
{
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
                $tools->append(new ClearNotificationButton('notifications',___('clear')));
            });

            $grid->model()->where('notifiable_id', Admin::user()->id)->orderBy('created_at', 'desc');

            $grid->column('created_at')->display(function ($created_at) {
                /** @var DatabaseNotification $this */
                $t =  Carbon::parse($created_at)->diffForHumans();

                if(is_null($this->read_at)) {
                    return '<span class="label" style="background: #4863bf;">'.$t.'</span>';
                }

                return $t;
            });

            $grid->column('type')->display(function($type) {
                return ___(Str::remove('App\\Notifications\\', $type ));
            });
            $grid->column('data')->display(function($data) {
                return ( isset($data['message']) ? $data['message'] : '' );
            });

            $grid->filter(function(Filter $filter) {
                $types = DatabaseNotification::where('notifiable_id', Admin::user()->id)->distinct()->pluck('type');
                foreach($types as $type) {
                    $key = Str::remove('App\\Notifications\\', $type );
                    $label = ___($key);

                    $filter->scope($key, $label)->where('type', $type);
                }

            });

            //$grid->disableFilterButton();
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