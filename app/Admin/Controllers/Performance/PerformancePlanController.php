<?php

namespace App\Admin\Controllers\Performance;

use App\Enums\YesNoType;
use App\Models\PerformancePlan;
use App\Models\User;
use App\Models\UserBrokerServer;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class PerformancePlanController extends AdminController
{

    protected function detail($id)
    {

        $show = new Show(PerformancePlan::findOrFail($id));

        $show->field('title');
        $show->divider();

        $show->field('initial_balance')->prepend('$');
        $show->field('leverage')->prepend('1:');
        $show->field('price')->prepend('$');

        $show->divider();
        $show->field('max_daily_loss_perc')->append('%');
        $show->field('max_loss_perc')->append('%');
        $show->field('profit_perc')->append('%');
        $show->field('min_trading_days', ___('min_trading_days'));
        $show->field('max_trading_days', ___('max_trading_days'));

        $show->field('check_hedging')->enum(YesNoType::class);
        $show->field('check_sl')->enum(YesNoType::class);

        return $show;
    }

    protected function grid()
    {
        return new Grid( new PerformancePlan(), function (Grid $grid) {
            $fmt = function ($val)
            {
                return $val.'%';
            };

           $broker_id = function($broker_id) {
                return $broker_id;
            };
            // dd($broker_id);
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id();
 //           $grid->column('key');
            // $name =  UserBrokerServer
            //     ::with('broker_server')
            //     ->enabled()->where('id',$grid->broker_id())
            //     ->whereHas('broker_server', static function ($server) {
            //         $server->api();
            //     })
            //     ->whereUserId(User::GetManagerId())->first();
            //     dd($name->broker_server->name);
            $grid->column('title');
            $grid->column('broker_id')->display($broker_id);

            $grid->column('initial_balance')->price();

            $grid->column('leverage')->display(function($leverage) {
                return '1:'.$leverage;
            });
            $grid->column('max_daily_loss_perc')->display($fmt);
            $grid->column('max_loss_perc')->display($fmt);
            $grid->column('profit_perc')->display($fmt);
            $grid->combine('trading_days', ['min_trading_days','max_trading_days'],___('trading_days'));
            $grid->column('min_trading_days');
            $grid->column('max_trading_days');
            $grid->column('price')->price();
            $grid->column('check_hedging')->bool()->help(___('help_check_hedging'));
            $grid->column('check_sl')->bool()->help(___('help_check_sl'));

            $grid->column('enabled')->switch();
            $grid->column('is_public')->switch();

            $grid->filter(function ($filter) {
                $filter->like('title');
                $filter->disableIdFilter();
            });

            $grid->disableFilterButton();
            $grid->disableRefreshButton();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        });
    }

    protected function form()
    {
        return new Form( new PerformancePlan(), function (Form $form) {
            $form->display('id');
            $form->hidden('manager_id')->value(Admin::user()->id);

            $form->text('key')->required();
            $form->text('title')->required();

            $options  = UserBrokerServer
                ::with('broker_server')
                ->enabled()
                ->whereHas('broker_server', static function ($server) {
                    $server->api();
                })
                ->whereUserId(User::GetManagerId())->get();
            $arr = array();
            foreach ($options as $option) {
                $arr[$option->id] = $option->broker_server->name;
            }

            $form->select('broker_id')->options($arr)->required();
            $form->number('initial_balance')->required();
            $form->number('leverage')->required();
            $form->divider(___('rules'));
            $form->number('max_daily_loss_perc')->required();
            $form->number('max_loss_perc')->required();
            $form->number('profit_perc')->required();
            $form->number('min_trading_days',___('min_trading_days'))->required();
            $form->number('max_trading_days', ___('max_trading_days'))->required()->help(___('help_max_trading_days'));
            $form->number('price')->required();
            $form->switch('check_hedging')->default(0)->help(___('help_check_hedging'));
            $form->switch('check_sl')->default(0)->help(___('help_check_sl'));
            $form->divider();
            $form->switch('enabled')->default(1);
            $form->switch('is_public')->default(0);

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }
}
