<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\DetailsLinkButton;
use App\Admin\Grids\TradingObjectiveGrid;

use App\Admin\Views\UserAccountsShortView;
use App\Admin\Views\UserChallengesShortView;
use App\Admin\Views\UserSignalsShortView;
use App\Models\CopierSignal;
use App\Models\User;
use App\Models\UserWithCount;
use App\Notifications\UserFollowed;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends AdminController
{
    protected $translation = 'user';

    public function follow(Request $request, $id)
    {
        $follower = Admin::user();

        $user = User::findOrFail($id);

        if ( ! $follower->isFollowing($user->id)) {
            $follower->follow($user->id);

            $user->notify(new UserFollowed($follower));

            return back()->withSuccess("You are now following {$user->name}");
        }

        return back()->withSuccess("You are already following {$user->name}");
    }

    public function impersonate(Request $request, $id)
    {
        if ($id == Admin::user()->id) {
            admin_toastr(__('admin.cant_impersonate_yourself'), 'error');
            return redirect('/');
        }

        app('impersonate')->login(User::find($id));

        return admin_redirect('/');
        //return response("<script>location.href = '/';</script>");
    }

    public function deimpersonate(Request $request)
    {
        if (app('impersonate')->isActive()) {
            app('impersonate')->logout();
        }

        if ($request->has('redirect_to')) {
            return admin_redirect($request['redirect_to']);
            //return response("<script>location.href = '{$request['redirect_to']}';</script>");
        }

        return admin_redirect('/');
        //return response("<script>location.href = '/';</script>");
    }

    protected function detail($id)
    {
        $show = new Show(User::with(['setting'])->findOrFail($id));

        if($id == Admin::user()->id) {
            $show->disableEditButton();
            $show->disableDeleteButton();
        }

        $show->id();
        $show->username();
        $show->name();
        $show->email();
        $show->api_token();

        if(Admin::user()->can('mng.copiers'))
            $show->field('setting.max_signals');

        if(Admin::user()->can('mng.objectives'))
            $show->field('setting.max_challenges');

        $show->field('setting.max_accounts');
        $show->created_at();
        $show->updated_at();

        $show->tools(function(Show\Tools $tools) {
            $tools->append(new DetailsLinkButton(___('impersonate'), 'fa-hand-o-down',admin_url('user/impersonate').'/'.$this->id));
        });

        $show->relation('accounts', ___('accounts'), function (User $user) {

            return new Grid($user->accounts()->getQuery()->with('broker_server'), function (Grid $grid) {

                $grid->disableBatchActions();
                $grid->disableCreateButton();
                $grid->disableRowSelector();
                $grid->disableActions();
                $grid->disableRefreshButton();

                $grid->account_number()->link(function() {
                    return admin_url('accounts') . '/' . $this->id;
                },'');
                $grid->column('title')->link(function() {
                    return admin_url('accounts') . '/' . $this->id;
                },'');
                $grid->column('broker_server.type', ___('type'))->enum();
                $grid->column('broker_server.name', ___('broker'));
                $grid->column('account_status')->enum();
                $grid->created_at();
                $grid->updated_at();
            });

        });

        if(Admin::user()->can('mng.copiers')) {
            $show->relation('signals', ___('signals'), function (User $user) {

                return new Grid($user->signal_subscriptions()->with('signal')->getQuery(), function (Grid $grid) {

                    $grid->disableBatchActions();
                    $grid->disableCreateButton();
                    $grid->disableRowSelector();
                    $grid->disableActions();
                    $grid->disableRefreshButton();

                    $grid->column('signal.title');
                    $grid->created_at();
                    $grid->updated_at();
                });

            });
        }

        if(Admin::user()->can('mng.objectives')) {
            $show->relation('performances', ___('trading-objectives'), function (User $user) {

                return new TradingObjectiveGrid($user->performancesWithObjectives()->with(['target', 'stat', 'user', 'plan'])->getQuery(), function (Grid $grid) {
                });
            });
        }

        return $show;
    }

    protected function grid()
    {
        return new Grid(UserWithCount::with(['setting','signals']), function (Grid $grid) {
            $grid->model()->where('manager_id', User::GetManagerId());

            $grid->id();
            $grid->column('name')->link(function() {
                return admin_url('clients') . '/' . $this->id;
            },'');
            $grid->column('email')->link(function() {
                return admin_url('clients') . '/' . $this->id;
            },'');

            $grid->combine('_accounts', ['countAccounts', 'setting.max_accounts'], ___('accounts'));
            $grid->column('countAccounts',___('cur'))
                ->expand(function() {
                    return UserAccountsShortView::make($this->id);
                });

            $grid->column('setting.max_accounts',___('max'));

            if(Admin::user()->can('mng.copiers')) {
                $grid->combine('_signals', ['countSignals', 'setting.max_signals'], ___('signals'));
                $grid->column('countSignals',___('cur'))
                     ->expand(function() {
                         return UserSignalsShortView::make($this->id);
                     })
                    ;

                $grid->column('setting.max_signals',___('max'));
            }

            if(Admin::user()->can('mng.objectives')) {
                $grid->combine('_challenges', ['countChallenges', 'setting.max_challenges'], ___('challenges'));
                $grid->column('countChallenges',___('cur'))
                     ->expand(function() {
                         return UserChallengesShortView::make($this->id);
                     })
                    ;

                $grid->column('setting.max_challenges',___('max'));
            }

            if(Admin::user()->can('impersonatable'))
                $grid->column('_impersonate')
                    ->display('Go')
                    ->help(___('help_impersonate'))
                    ->link(function() {
                        return admin_url('/user/impersonate/'.$this->id);
                    }, '');

            $grid->column('created_at')->sortable();
            $grid->column('updated_at');

            $grid->quickSearch(['email','name']);
            $grid->filter(function ($filter) {
                $filter->like('email');
                $filter->like('name');
                $filter->disableIdFilter();
            });

            $grid->hideColumns(['created_at', 'updated_at']);
            $grid->showColumnSelector();

            $grid->export()->driver()->csv()->titles([
                'id' => ___('id'),
                'name' =>___('name'),
                'email' => ___('email'),
                'created_at' => ___('created'),
                'updated_at' => ___('updated')
            ]);
        });
    }

    protected function form()
    {
        return new Form(User::with('setting'), function (Form $form) {

            $id = $form->getKey();

            if($form->isEditing())
                $apiToken = $form->model()->api_token;
            else
                $apiToken = Str::random(12);

            $form->display('id', 'ID');
            $form->hidden('manager_id')->value(User::GetManagerId());
            $form->hidden('api_token')->value($apiToken);

            $form->email('email', 'Email')->required()
                ->creationRules(['required', "unique:admin_users"])
                ->updateRules(['required', "unique:admin_users,email,{{id}}"]);

            $form->text('username', 'Login')->required()
                ->creationRules(['required', "unique:admin_users"])
                ->updateRules(['required', "unique:admin_users,username,{{id}}"]);

            $form->text('name', trans('admin.full_name'))->rules('required')->required();
            //$form->switch('activated', 'Activated');
            $form->text('api_token_s', 'API token')
                ->disable()
                ->default(
                    function () use($form, $apiToken) {
                        if($form->isEditing())
                            return $form->model()->api_token;
                        return $apiToken;
                    }
                );

            if ($id) {
                $form->password('password', trans('admin.password'))
                    ->minLength(5)
                    ->maxLength(20)
                    ->customFormat(function () {
                        return '';
                    });
            } else {
                $form->password('password', trans('admin.password'))
                    ->required()
                    ->minLength(5)
                    ->maxLength(20);
            }

            $form->password('password_confirmation', trans('admin.password_confirmation'))->same('password');

            if(Admin::user()->can('mng.copiers')) {
                $options = CopierSignal::whereManagerId(Admin::user()->id)->pluck('title', 'id');
                $form->multipleSelect('signals')
                    ->options($options)
                    ->customFormat(function ($v) {
                        return array_column($v, 'id');
                    });

                $form->number('setting.max_signals');
            }

            if(Admin::user()->can('mng.objectives')) {
                $form->number('setting.max_challenges');
            }

            $form->number('setting.max_accounts')->default(1);

            $form->ignore(['password_confirmation', 'api_token_s']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->get('password') != $form->password) {
                    $form->password = bcrypt($form->password);
                }

                if (! $form->password) {
                    $form->deleteInput('password');
                }
            });
        });
    }

}
