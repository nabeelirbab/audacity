<?php

namespace App\Admin\Controllers\Performance;

use App\Admin\Extensions\Tools\RertyFailedChallengeBatchAction;
use App\Admin\Grids\TradingObjectiveGrid;
use App\Admin\RowActions\CancelChallenge;
use App\Admin\RowActions\ConfirmChallenge;
use App\Enums\ChallengeStatus;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Column\Help;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Illuminate\Support\Facades\Request;

class ChallengeController extends AdminController
{
    protected $translation = 'challenge';

    protected function detail($id)
    {
        $show = new Show(Challenge::with(['plan', 'account', 'user', 'stat'])->findOrFail($id));
        $show->disableDeleteButton();
        $show->disableEditButton();

        $show->field('status', ___('status'))->enum(ChallengeStatus::class);
        $show->field('plan.initial_balance', ___('initial_balance'))->prepend('$');
        $show->field('plan.leverage', ___('leverage'))->prepend('1:');
        $show->field('plan.price', ___('price'))->append('$');

        $show->divider();
        $show->field('user.name', ___('user_name'));
        $show->field('user.email', ___('user_email'));

        $show->divider();

        if($show->model()->status == ChallengeStatus::ACTIVE || $show->model()->status == ChallengeStatus::ENDED) {
            $show->field('account.account_number', ___('account_number'));
            $show->field('account.password', ___('account_password'));
            $show->field('account.broker_server_name', ___('broker_name'));
        } else {
            $show->html(___('help_wait_activation'));
        }

        $show->relation('trading_objectives', ___('trading_objectives'), function (Challenge $challenge) {

            return new TradingObjectiveGrid( $challenge->performanceWithObjectives()->with('plan')->getQuery(), function (Grid $grid) {
            });

        });

        return $show;
    }


    protected function grid()
    {
        return new Grid( Challenge::with(['plan','user']), function (Grid $grid) {

            $selector = Request::get('_selector');

            $statusSelected = ChallengeStatus::ACTIVE;
            if ($selector && is_array($selector) && isset($selector['status'])) {
                $statusSelected = $selector['status'];
            }

            $grid->selector(function(Grid\Tools\Selector $selector) {
                $selector->selectOne('status', ___('status'), ChallengeStatus::map(),
                function($query, $value) {
                    $query->where('status', $value);
                });
            });

            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->column('id', ___('id'));
            $grid->column('user.name', ___('user'))->link(function() {
                return admin_route('clients.index') .'/' . $this->user_id;
            },'');
            $grid->column('user.email');

            $grid->column('plan.title', ___('plan'))->link(function() {
                return admin_route('plans.index') .'/' . $this->performance_plan_id;
            },'');

            $grid->column('plan.price', ___('price'))->price();

            $grid->column('status')->display(function(ChallengeStatus $status) use($statusSelected) {
                if($status == ChallengeStatus::ERROR && $statusSelected != ChallengeStatus::ERROR->value) {
                    $help = new Help($this->last_error, 'red');
                    return $status->label().$help->render();
                }
                return $status->label();
            });

            if($statusSelected == ChallengeStatus::ERROR->value) {
                $grid->column('last_error');
            }

            $grid->actions(function(Actions $actions) {
                if($actions->row->status == ChallengeStatus::NEW) {
                    $actions->append(new ConfirmChallenge());
                }

                if($actions->row->status != ChallengeStatus::CANCELLED) {
                    $actions->append(new CancelChallenge());
                }
            });

            $grid->column('updated_at')->dateHuman();

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->divider();
                    $batch->add(new RertyFailedChallengeBatchAction(___('retry_failed')));
                });

            });

            $grid->disableEditButton();
        });
    }

    protected function form()
    {
        return new Form( Challenge::with(['user', 'plan']), function (Form $form) {

            $form->hidden('manager_id')->value(Admin::user()->id);

            $options = User::whereManagerId(Admin::user()->id)->pluck('name', 'id');
            $options->prepend(Admin::user()->name, Admin::user()->id);
            $form->select('user_id', 'User')->options($options)->required();

            $options  = PerformancePlan
                ::enabled()
                ->whereManagerId(Admin::user()->id)
                ->pluck('title', 'id');

            $form->select('performance_plan_id', ___('plan'))
                ->options($options)
                ->required()
                ->help(___('help_add_plan',
                    ['link'=>'<a href="'.admin_url('plans').'">'.__('admin.click').'</a>' ]));

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}