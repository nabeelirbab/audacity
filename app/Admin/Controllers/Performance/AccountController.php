<?php

namespace App\Admin\Controllers\Performance;

use App\Admin\Controllers\AccountController as BaseController;
use App\Admin\Extensions\Tools\MoveAccountBatchAction;
use App\Admin\Extensions\Tools\RestartAccountBatchAction;
use App\Admin\Extensions\Tools\SuspendAccountBatchAction;
use App\Admin\RowActions\RestartAccount;
use App\Enums\AccountStatus;
use App\Enums\CopierType;
use App\Models\Account;
use App\Models\ApiServer;
use App\Models\Performance;
use App\Models\PerformancePlan;
use App\Models\User;
use App\Models\UserBrokerServer;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;

class AccountController extends BaseController
{
    protected $translation = 'performance-account';

    protected function grid()
    {
        return new Grid( Account::with(['stat','user']), function (Grid $grid) {

            $this->checkApiServer();

            $grid->selector(function(Grid\Tools\Selector $selector) { return self::buildStatusSelector($selector);} );
            $grid->model()->whereManagerId(User::GetManagerId());

            $grid->account_number()->link(function() use($grid) {
                return $grid->resource().'/' . $this->id;
            },'');
            $grid->column('title');
            $grid->column('user.name', ___('user'))->link(function() {
                return admin_route('clients.index') .'/' . $this->user_id;
            },'');
            $grid->column('user.email');
            $grid->broker_server_name()->filter();

            $grid->column('account_status')->display(function($status) {
                if($this->wait_restarting == 1)
                    return ___('restarting');

                return $this->status->label();
            });

            $grid->column('updated_at')->dateHuman();

            $grid->api_version('Ver#');

            $grid->quickSearch(['title','account_number']);
            $grid->filter(function ( $filter) {
                $filter->like('title');

                $options  = UserBrokerServer
                    ::with('broker_server')
                    ->enabled()
                    ->whereHas('broker_server', static function ($server) {
                        $server->api();
                    })
                    ->whereUserId(User::GetManagerId())->get();
                $arr = array();
                foreach ($options as $option) {
                    $arr[$option->broker_server->name] = $option->broker_server->name;
                }
                $filter->equal('broker_server_name')->select($arr);

                if (Admin::user()->can('mng.list_api_servers')) {
                    $servers = ApiServer::whereManagerId(User::GetManagerId())->pluck('title', 'ip');
                    $filter->equal('api_server_ip')->select($servers);
                }

                $filter->between('created_at')->datetime();
                $filter->disableIdFilter();
            });

            $grid->actions(function(Actions $actions) {
                if($actions->row->wait_restarting == 0)
                    $actions->prepend(new RestartAccount());
            });

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->divider();
                    $batch->add(new RestartAccountBatchAction(___('restart')));
                    $batch->add(new SuspendAccountBatchAction(___('suspend')));

                    if (Admin::user()->can('mng.list_api_servers')) {
                        $servers = ApiServer::whereManagerId(User::GetManagerId())->withCount('accounts')->get();

                        if(count($servers) > 1) {
                            $batch->divider();
                            foreach ($servers as $server) {
                                $free = $server->max_accounts - $server->count_accounts;
                                $title = $server->title.' ('. $free. ')';
                                $batch->add(new MoveAccountBatchAction($server->ip, ___('move_to').' <b>'.$title. '</b>'));
                            }
                        }
                    }
                });

            });
            $grid->showColumnSelector();
            $grid->hideColumns(['api_version']);
            //$grid->export();
        });
    }

    protected function form()
    {
        return new Form( new Account(), function (Form $form) {

            $form->hidden('creator_id')->value(User::GetManagerId());
            $form->hidden('manager_id')->value(User::GetManagerId());

            $options = User::whereManagerId(User::GetManagerId())->pluck('name', 'id');
            $options->prepend(Admin::user()->name, Admin::user()->id);
            $form->select('user_id', 'User')->options($options)->required();

            $options  = UserBrokerServer
                ::with('broker_server')
                ->enabled()
                ->whereHas('broker_server', static function ($server) {
                    $server->api();
                })
                ->whereUserId(Admin::user()->id)->get();
            $arr = array();
            foreach ($options as $option) {
                $arr[$option->broker_server->name] = $option->broker_server->name;
            }
            $form->select('broker_server_name', 'Broker Server')
                ->options($arr)
                ->required()
                ->help(___('help_add_broker',
                    ['link'=>'<a href="'.admin_url('broker-servers').'">'.__('admin.click').'</a>' ]));

            if ($form->isEditing()) {
                $form->text('account_number', 'Account Number')->disable();
            } else {
                $form->text('account_number', 'Account Number')
                    ->required()
                    ->creationRules(["unique:accounts"]);
            }

            $form->text('password')->required();
            $form->hidden('copier_type')->value(CopierType::SENDER);

            $form->text('title', 'Title');
            $form->text('suffix');

            if (Admin::user()->can('mng.list_api_servers')) {
                $servers = ApiServer::whereManagerId(User::GetManagerId())->pluck('title', 'ip');
                $def = $servers->keys()->first();

                $form->select('api_server_ip')->options($servers)->default($def)->required();
            }

            $plans = PerformancePlan::whereManagerId(Admin::user()->id)->enabled()->pluck('title', 'id');
            $form->select('_plan')->options($plans)->required();

            $form->ignore('_plan');

            $form->display('created_at');
            $form->display('updated_at');

            $form->saved(function(Form $form) {
                Performance::createForAccountPlanId($form->repository()->model(), request(['_plan'])['_plan']);
            });
        });
    }
}

