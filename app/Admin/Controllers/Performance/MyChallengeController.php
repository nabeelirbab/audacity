<?php

namespace App\Admin\Controllers\Performance;

use App\Admin\RowActions\CancelChallenge;

use App\Enums\ChallengeStatus;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use App\Models\UserSetting;
use App\Notifications\ChallengeCreatedNotification;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class MyChallengeController extends AdminController
{
    protected $translation = 'challenge';

    protected function detail($id)
    {
        $show = new Show(Challenge::with(['plan', 'account'])->findOrFail($id));
        $show->disableDeleteButton();
        $show->disableEditButton();

        $show->field('status', ___('status'))->as(function() {
            return $this->status->label();
        });

        $show->field('plan.title', ___('plan_title'));
        $show->field('plan.max_daily_loss_perc', ___('plan_max_daily_loss_perc'));
        $show->field('plan.max_loss_perc', ___('plan_max_loss_perc'));
        $show->field('plan.profit_perc', ___('plan_profit_perc'));
        $show->field('plan.min_trading_days', ___('plan_min_trading_days'));
        $show->field('plan.max_trading_days', ___('plan_max_trading_days'));
        $show->field('plan.initial_balance', ___('plan_initial_balance'));
        $show->field('plan.leverage', ___('plan_leverage'));
        $show->field('plan.price', ___('plan_price'));

        $show->divider();

        if($show->model()->status != ChallengeStatus::CANCELLED) {
            if($show->model()->status == ChallengeStatus::ACTIVE) {
                $show->field('account.account_number', ___('account_number'));
                $show->field('account.password', ___('account_password'));
                $show->field('account.broker_server_name', ___('broker_name'));
            } else {
                $show->html(___('help_wait_activation'));
            }
        }

        return $show;
    }

    protected function grid()
    {
        return new Grid( Challenge::with(['plan', 'account']), function (Grid $grid) {

            $grid->model()->whereUserId(Admin::id());

            $grid->column('id', ___('id'));
            $grid->column('plan.title', ___('plan'));

            $grid->column('plan.price', ___('price'))->price();

            $grid->column('updated_at')->dateHuman();

            $grid->column('status')->enum();

            $grid->column('details', ___('details'))
                ->icon('eye', ___('analysis'))
                ->link(function() {
                    return admin_url('my-challenges').'/'.$this->id;
                },'');

            $grid->actions(function(Actions $actions) {
                if($actions->row->status == ChallengeStatus::NEW) {
                    $actions->append(new CancelChallenge(true));
                }
            });

            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableRowSelector();
            $grid->disableEditButton();

            $settings = UserSetting::whereUserId(Admin::id())->first();

            if($settings && $settings->max_challenges > 0 ) {
                $countChallenges = Challenge::whereUserId(Admin::id())->count();

                if( $countChallenges >= $settings->max_challenges) {
                    $grid->disableCreateButton();
                }
            }

            if(Admin::user()->cannot('user.manage_challenges')) {
                $grid->disableCreateButton();
            }
        });
    }

    protected function form()
    {
        return new Form( Challenge::with(['plan']), function (Form $form) {

            $form->hidden('manager_id')->value(Admin::user()->manager_id);
            $form->hidden('user_id')->value(Admin::user()->id);
            $form->hidden('price')->value(0);

            $plans  = PerformancePlan
                ::whereManagerId(Admin::user()->manager_id)
                ->where('is_public',1)
                ->get();

            $r = $form->radio('performance_plan_id', ___('plan'));

            foreach($plans as $plan) {
                $r->when($plan->id, function(Form $form) use($plan) {
                    $form->html($this->_formatPlanOutput($plan));
                });
            }

            $options = $plans->pluck('title', 'id');
            $r->options($options);
            if(!is_null($plans->first()))
                $r->default($plans->first()->id);

            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(function(Form $form) use($plans) {
                $form->price = $plans->find($form->performance_plan_id)->price;
            });

            $form->saved(function(Form $form) {

                if($form->repository()->model()->wasRecentlyCreated) {
                    $order = $form->repository()->model();
                    $plan = $order->plan;

                    Admin::user()->notify(new ChallengeCreatedNotification($order, $plan, Admin::user()->manager_id ));
                }
            });
        });

    }

    private function _formatPlanOutput( PerformancePlan $plan) {
        return <<<HTML
        <style>
            li {
                width: 100%;
                display: flex;
                position: relative;
                box-sizing: border-box;
                text-align: left;
                align-items: center;
                padding-top: 8px;
                padding-bottom: 8px;
                justify-content: flex-start;
                text-decoration: none;
            }
        </style>
        <div>
            <ul>
                <li>
                    <div>
                        <b>{$plan->price} USD</b> Fee
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->max_daily_loss_perc}%</b> Daily Drawdown
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->max_loss_perc}%</b> Overall Drawdown
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->profit_perc}%</b> Min Profit
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->min_trading_days}</b> Min Trading Days
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->max_trading_days}</b> Max Trading Days
                    </div>
                </li>
                <li>
                    <div>
                        <b>{$plan->initial_balance} USD</b> Initial Balance
                    </div>
                </li>
                <li>
                    <div>
                        <b>1:{$plan->leverage}</b> Leverage
                    </div>
                </li>
            </ul>
        </div>
        HTML;
    }

}