<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\DetailsLinkButton;
use App\Admin\Extensions\Tools\MoveAccountBatchAction;
use App\Admin\Extensions\Tools\OrderCloseBatchAction;
use App\Admin\Extensions\Tools\OrderDeleteBatchAction;
use App\Admin\Extensions\Tools\ResetAccountStatButton;
use App\Admin\Extensions\Tools\RestartAccountBatchAction;
use App\Admin\Extensions\Tools\RestartAccountButton;
use App\Admin\Extensions\Tools\SuspendAccountBatchAction;
use App\Admin\Grids\TradingObjectiveGrid;
use App\Admin\RowActions\RestartAccount;
use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Enums\BrokerServerType;
use App\Enums\CopierType;
use App\Enums\MetatraderType;
use App\Enums\YesNoType;
use App\Helpers\MT4TerminalApi;
use App\Helpers\MT5TerminalApi;
use App\Models\Account;
use App\Models\ApiServer;
use App\Models\CopierSignalFollower;
use App\Models\Performance;
use App\Models\PerformancePlan;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserBrokerServer;
use App\Models\UserSetting;
use App\Notifications\AccountGeneratedNotification;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Column\Help;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class AccountController extends AdminController
{

    protected $translation = 'account';

    protected function checkApiServer() {
        if(!ApiServer::whereManagerId(User::GetManagerId())->exists()) {
            admin_toastr(___('error_missing_api_server'), 'error');
            return false;
        }

        if(!ApiServer::getOneWithFreeSpace(User::GetManagerId())) {
            admin_toastr(___('error_api_servers_full'), 'error');
            return false;
        }

        return true;
    }

    protected static function buildStatusSelector(Grid\Tools\Selector $selector) {

        $statuses = [
            AccountStatus::ONLINE->value => AccountStatus::ONLINE->label(),
            AccountStatus::OFFLINE->value => AccountStatus::OFFLINE->label(),
            AccountStatus::INVALID->value => AccountStatus::INVALID->label(),
            AccountStatus::PENDING->value => AccountStatus::PENDING->label(),
        ];

        if(Account::hasOrfant(Admin::user()->id)) {
            $statuses[AccountStatus::ORFANT->value] = AccountStatus::ORFANT->label();
        }

        $selector->select('status', $statuses, function($query, $values) {

            $st = array();
            $status = array();
            foreach($values as $val) {
                $st = match((int)$val) {
                    AccountStatus::ONLINE->value => [AccountStatus::ONLINE->value, AccountStatus::CNN_LOST->value],
                    AccountStatus::OFFLINE->value => [AccountStatus::OFFLINE->value, AccountStatus::SUSPEND->value, AccountStatus::SUSPEND_STOPPED->value],
                    AccountStatus::INVALID->value => [AccountStatus::INVALID->value, AccountStatus::INVALID_STOPPED->value],
                    AccountStatus::PENDING->value => [AccountStatus::PENDING->value, AccountStatus::NONE->value, AccountStatus::REMOVING->value, AccountStatus::VERIFYING->value],
                    default => [$val]
                };

                $status = array_merge($status, $st);
            }

            $query->whereIn('account_status', array_unique($status));
        });
    }

    protected function detail($id)
    {

        $show = new Show(Account::with('user', 'broker_server')->findOrFail($id));

        $show->field('user.name', ___('user'));

        $show->field('account_status')->enum(AccountStatus::class);

        $show->field('account_number');
        $show->field('password');
        $show->field('name');
        $show->field('title');
        $show->field('broker_server.type')->enum(MetatraderType::class);
        $show->field('broker_server_name');
        $show->field('suffix');
        $show->field('is_live','Type')->enum(AccountType::class);
        $show->field('trade_allowed')->enum(YesNoType::class);

        $show->field('symbol_trade_allowed')->enum(YesNoType::class);

        if (Admin::user()->can('mng.list_api_servers')) {
            $show->field('api_server_ip');
        }
        $show->field('last_error');
        $show->field('build', ___('mt4_version'));
        $show->field('api_version');

        $show->created_at();
        $show->updated_at();

        $show->tools(function(Show\Tools $tools) {
            /** @var Account $this */
            $tools->append(new ResetAccountStatButton(___('reset_stat'), $this->account_number));
            $tools->append(new RestartAccountButton(___('restart'), $this->id));
            $tools->append(new DetailsLinkButton(___('view_stat'), 'fa-signal',admin_url('account-analysis').'/'.$this->account_number));
        });

        $show->relation('liveorders', 'Live Orders', function (Account $account) {

            return new Grid( $account->liveorders()->getQuery(), function (Grid $grid) {
                $grid->setKeyName('ticket');
                $grid->disableActions();
                $grid->disableFilter();
                $grid->disableCreateButton();

                $grid->ticket();
                $grid->symbol();
                $grid->type_str('Type')->sortable();
                $grid->lots();
                $grid->price();
                $grid->stoploss('Stoploss');
                $grid->takeprofit('TakeProfit');
                $grid->time_open('Time');
                $grid->magic();
                $grid->comment();

                $grid->tools(function ($tools) {

                    $tools->batch(function (Grid\Tools\BatchActions $batch)  {
                        $batch->add(new OrderCloseBatchAction(___('close')));
                    });
                });

            });

        });

        $show->relation('closedorders','Closed Orders', function (Account $account) {
            return new Grid($account->closedorders()->getQuery(), function (Grid $grid) {

                $grid->disableActions();
                //$grid->disableFilter();
                $grid->disableCreateButton();

                $grid->symbol('Symbol');
                $grid->type_str('Type')->sortable();
                $grid->lots('Lots');
                $grid->price('Price');
                $grid->stoploss('Stoploss');
                $grid->takeprofit('TakeProfit');
                $grid->time_open('Time');
                $grid->time_close('Time');
                $grid->price_close('Price');
                $grid->pl('P/L');
                $grid->pips('Pips');
                $grid->magic('Magic');
                $grid->comment('Comment');

                $grid->filter(function ( Grid\Filter $filter) {
                    $filter->lt('pl', ___('pl_less'));
                });

                $grid->tools(function ($tools) {

                    $tools->batch(function (Grid\Tools\BatchActions $batch)  {
                        //$batch->disableDelete(false);
                        $batch->add(new OrderDeleteBatchAction(___('delete')));
                    });
                });
            });

        });


        if( Admin::user()->can('mng.copiers') && $show->model()->copier_type == CopierType::FOLLOWER) {
            $show->relation('following','Following', function (Account $account) {
                return new Grid(CopierSignalFollower::with(['signal']), function (Grid $grid) use($account) {
                    $grid->model()->whereAccountId($account->id);
                    $grid->disableActions();
                    $grid->disableFilter();
                    $grid->disableCreateButton();

                    $grid->column('signal.title', ___('signal'))->link(function() {
                        /** @var Account $this */
                        return admin_url('followers') . '?signal_id=' . $this->signal_id.'&_search_='.$this->account_id;
                        },'');

                    $grid->column('risk_type')->display(function() {
                        return CopierSignalFollower::formatRiskString($this);
                    });
                    $grid->column('max_lots_per_trade');
                    $grid->column('max_open_positions');

                    $grid->column('copier_enabled')->bool();
                    $grid->column('email_enabled')->bool();
                    $grid->column('reverse_copy')->bool();
                });

            });
        }

        if(Admin::user()->can('mng.objectives')) {
            $show->relation('performances', ___('trading_objectives'), function (Account $account) {

                return new TradingObjectiveGrid($account->performancesWithObjectives()->with(['target', 'stat', 'user', 'plan'])->getQuery(), function (Grid $grid) {
                });
            });
        }

        return $show;
    }

    protected function grid()
    {
        return new Grid( Account::with(['stat','user','asFollower','asSender', 'tags', 'broker_server']), function (Grid $grid) {

            $this->checkApiServer();

            $settings = UserSetting::whereUserId(Admin::user()->id)->first();

            if($settings && $settings->max_accounts > 0 ) {
                $countAccounts = Account::whereManagerId(User::GetManagerId())->count();

                if( $countAccounts >= $settings->max_accounts) {
                    $grid->disableCreateButton();
                }
            }

            $grid->selector(function(Grid\Tools\Selector $selector) { return self::buildStatusSelector($selector);} );
            $grid->model()->whereManagerId(User::GetManagerId());

            $grid->column('account_number', 'title')
                ->display(function() {
                    /** @var Account $this */
                    return $this->account_number == $this->title
                        ? $this->account_number
                        : $this->title.'&nbsp;&nbsp;('.$this->account_number.')';
                })
                ->link(function() use($grid) {
                    /** @var Account $this */
                    return $grid->resource().'/' . $this->id;
                },'');

            $grid->column('user.name', ___('user'))->link(function() {
                /** @var Account $this */
                return admin_route('clients.index') . '/' . $this->user_id;
            },'')->filter();
            $grid->column('user.email')->filter();
            $grid->column('broker_server.type')->enum();
            $grid->column('broker_server_name');

            if (Admin::user()->can('mng.copiers')) {
                $grid->column('copier_type');
            }

            $grid->column('account_status')->enum();

            $grid->column('updated_at')->dateHuman();

            $grid->api_version('Ver#');
            if (Admin::user()->can('mng.list_api_servers')) {
                $grid->column('api_server_ip');
            }

            $grid->column('tags')->display(function($tags) {
                $t = '';
                foreach ($tags as $tag) {
                    $t .= "<span title='{$tag->title}' class='label' style='background-color:{$tag->color}'>&nbsp;&nbsp;</span>";
                }

                return $t;
            });

            $grid->rows(function ($rows) {

                $rows->map(function ($row) {

                    if (Admin::user()->can('mng.copiers')) {
                        $items = array();
                        if ($row->copier_type == CopierType::SENDER->value) {
                            $items = $row->asSender;
                        } else {
                            $items = $row->asFollower;
                        }

                        $str = '';
                        foreach ($items as $item) {
                            $link = admin_url('followers?signal_id='.$item['id']);
                            $str .= '<a href="'.$link.'" class="label" style="background:#586cb1">' . $item['title'] . '</a> ';
                        }

                        if(!empty($str))
                            $str = ' '.___('of').' '.$str;

                        $row->column('copier_type', CopierType::from($row->copier_type)->label().$str);

                        $row->column('_', $this->formatErrorIcon($row));
                    }
                });
            });

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
                $filter->equal('broker_server.type')->select(MetatraderType::map());
                $filter->equal('broker_server_name')->select($arr);

                if (Admin::user()->can('mng.api_servers')) {
                    $servers = ApiServer::whereManagerId(User::GetManagerId())->pluck('title', 'ip');
                    $filter->equal('api_server_ip')->select($servers);
                }

                if(Admin::user()->can('mng.copiers')) {
                    $filter->equal('copier_type')->select([
                        CopierType::SENDER->value => CopierType::SENDER->label(),
                        CopierType::FOLLOWER->value => CopierType::FOLLOWER->label(),
                    ]);
                }

                $filter->between('created_at')->datetime();
                $filter->disableIdFilter();
            });

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->divider();
                    $batch->add(new RestartAccountBatchAction(___('restart')));
                    $batch->add(new SuspendAccountBatchAction(___('suspend')));

                    if (Admin::user()->can('mng.list_api_servers')) {
                        $servers = ApiServer::enabled()->whereManagerId(User::GetManagerId())->withCount('accounts')->get();

                        if(count($servers) > 1) {
                            $batch->divider();
                            foreach ($servers as $server) {
                                $free = $server->max_accounts - $server->accounts_count;
                                $title = $server->title.' ('. $free. ')';
                                $batch->add(new MoveAccountBatchAction($server->ip, ___('move_to').' <b>'.$title. '</b>'));
                            }
                        }
                    }
                });
            });

            $grid->actions(function(Actions $actions) {
                $actions->prepend(new RestartAccount());
            });
            $grid->showColumnSelector();

            $hidden = ['api_version', 'tags'];
            if (Admin::user()->can('mng.list_api_servers')) {
                $hidden[] = 'api_server_ip';
            }
            $grid->hideColumns($hidden);
        });
    }

    protected function form()
    {
        return new Form( Account::with(['tags']), function (Form $form) {

            $form->hidden('creator_id')->value(User::GetManagerId());
            $form->hidden('manager_id')->value(User::GetManagerId());

            $options = User::whereManagerId(User::GetManagerId())->pluck('name', 'id');
            $options->prepend(Admin::user()->name, Admin::user()->id);
            $form->select('user_id', 'User')->options($options)->required();

            $options  = UserBrokerServer
                ::with('broker_server')
                ->enabled()
                ->whereHas('broker_server', static function ($server) {
                    $server->api()->active();
                })
                ->whereUserId(Admin::user()->id)->get();
            $arr = array();
            foreach ($options as $option) {
                $arr[$option->broker_server->name] = $option->broker_server->name;
            }
            $form->select('broker_server_name')
                ->options($arr)
                ->required()
                ->help(___('help_add_broker',
                    ['link'=>'<a href="'.admin_url('broker-servers').'">'.__('admin.click').'</a>' ]));

            if ($form->isCreating()) {
                $form->radio('_existing_or_new', ___('existing_or_new'))
                    ->when(0, function(Form $form) {
                        $form->text('account_number')->creationRules(['unique:accounts']);
                        $form->text('password');
                    })
                    ->when(1, function(Form $form) {
                        $form->number('balance', ___('balance'))->default(100000);
                        $form->number('leverage', ___('leverage'))->default(100);
                        $form->html(___('help_generate_new_account'));
                    })
                ->options([0 => ___('add_existing'), 1 => ___('generate_new')])
                ->default(0);
                $form->ignore(['_existing_or_new', 'balance', 'leverage']);
            }

            if ($form->isEditing()) {
                $form->text('account_number')->disable();
            }

            if ($form->isCreating()) {
                if(Admin::user()->can('mng.copiers')) {
                    $options = [
                        CopierType::FOLLOWER->value => CopierType::FOLLOWER->label(),
                        CopierType::SENDER->value => CopierType::SENDER->label(),
                    ];
                    $form->radio('copier_type')->options($options)->default(CopierType::FOLLOWER->value);
                } else {
                    $form->hidden('copier_type')->value(CopierType::SENDER);
                }

                if(Admin::user()->can('mng.objectives')) {
                    $plans = PerformancePlan::whereManagerId(Admin::user()->id)->enabled()->pluck('title', 'id');
                    $plans->prepend(___('dont_check_objectives'), -1);
                    $form->select('_plan', ___('objectives_plan'))->options($plans)->required()->default(-1);

                    $form->ignore('_plan');
                }
            }

            $form->text('title');
            $form->text('suffix');

            if (Admin::user()->can('mng.list_api_servers')) {
                $servers = ApiServer::whereManagerId(User::GetManagerId())->pluck('title', 'ip');
                $def = $servers->keys()->first();
                $form->select('api_server_ip')->options($servers)->default($def)->required();
            }


            $tags = Tag::whereManagerId(Admin::user()->id)->get();
            $idTitles = array();
            $idColors = array();
            foreach ($tags as $tag) {
                $idTitles[$tag->id] = $tag->title;
                $idColors[$tag->id] = $tag->color;
            }
            $form->multipleSelect('tags')
                ->options($idTitles)
                ->customItemColors($idColors)
                ->customFormat(function ($v) {
                    return array_column($v, 'id');
                });

            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(function (Form $form) {
                $type = request('_existing_or_new');

                if($type == 1) {
                    $broker = UserBrokerServer::with('broker_server')
                        ->whereHas('broker_server', function($query) use($form) {
                            return $query->where('name', $form->broker_server_name );
                        })
                        ->firstOrFail();

                    $user = User::find($form->user_id);

                    $email = $user->email;
                    $name = $user->name;

                    $type = $broker->broker_server->type;

                    if($type == MetatraderType::V4) {
                        $json = MT4TerminalApi::CreateAccount($broker->broker_server->main_server_host,
                        $broker->broker_server->main_server_port,
                        $name, $email, request('balance'), request('leverage'), $broker->default_group,
                        $broker->broker_server->name, 'Country', 'City', 'State', 'Zip', 'Address', 'Phone');

                        if(!$json)
                            return false;

                        $mt4Account = json_decode($json);
                        $login = $mt4Account->user;
                        $password = $mt4Account->password;
                    }

                    if($type == MetatraderType::V5) {
                        $json = MT5TerminalApi::CreateAccount($name, $email, request('balance'),
                        request('leverage'), $broker->default_group,
                        $broker->broker_server->name, 'Country', 'City', 'State', 'Zip', 'Address', 'Phone');

                        if(!$json)
                            return false;

                        $mt5Account = json_decode($json);
                        $login = $mt5Account->login;
                        $password = $mt5Account->password;
                    }

                    $form->account_number = $login;
                    $form->password = $password;
                }

            });

            $form->saved(function(Form $form) {
                if( !is_null(request('_plan')) && request('_plan') != -1) {
                    Performance::createForAccountPlanId($form->repository()->model(), request('_plan'));
                }

                $type = request('_existing_or_new');

                if($type == 1) {
                    $user = User::find($form->user_id);
                    $user->notify(new AccountGeneratedNotification($form->repository()->model(), Admin::user()->id ));
                }
            });
        });
    }

    private static function formatErrorIcon($data) {

        $message = false;

        if($data->account_status == AccountStatus::INVALID->label()
            || $data->account_status == AccountStatus::INVALID_STOPPED->label()) {
            $message = $data->last_error;
        }

        if($data->trade_allowed == YesNoType::NO && $data->copier_type == CopierType::FOLLOWER) {
            $message = ___('error_follower_trade_not_allowed');
        }

        if($message) {
            return new Help($message, 'red');
        }

        return '';
    }

}

