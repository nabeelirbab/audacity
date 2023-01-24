<?php

namespace App\Admin\Controllers;

use App\Admin\RowActions\MarkBrokerServerAsDefault;
use App\Enums\BrokerServerStatus;
use App\Enums\MetatraderType;
use App\Helpers\MT5TerminalApi;
use App\Models\BrokerServer;
use App\Models\User;
use App\Models\UserBrokerServer;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserBrokerServerController extends AdminController
{

    protected function detail($id)
    {
        $show = new Show($id, UserBrokerServer::with(['broker_server']));

        $show->disableDeleteButton();
        $show->disableEditButton();
        $show->field('broker_server.name');
        $show->field('broker_server.suffix');
        $show->field('broker_server.main_server_host');
        $show->field('broker_server.main_server_port');
        $show->field('broker_server.status')->enum(BrokerServerStatus::class);

        $show->relation('server_hosts', ___('hosts'), function( UserBrokerServer $server) {
            return new Grid($server->server_hosts()->getQuery(), function(Grid $grid) {
                $grid->column('host');
                $grid->column('port');
                $grid->column('ping');
                $grid->column('status')->enum();
                $grid->column('is_main')->bool();

                $grid->disableActions();
                $grid->disableCreateButton();
                $grid->disableRowSelector();
            });
        });

        return $show;
    }

    protected function grid()
    {
        return new Grid(UserBrokerServer::with('broker_server'), function (Grid $grid) {
            $grid->model()->whereUserId(User::GetManagerId());

            $grid->model()->orderBy('is_default', 'desc')->orderBy('enabled', 'desc');

            $grid->id();
            $grid->column('broker_server.type')->enum();
            $grid->column('broker_server.name')->link(function() use($grid) {
                return $grid->resource().'/'.$this->id;
            },'');
            $grid->column('broker_server.suffix');

            $grid->column('broker_server.updated_at');

            $grid->enabled()->switch();
            $grid->column('broker_server.status')->enum();
            $grid->column('default_group');

            $grid->column('is_default')->bool();

            $grid->quickSearch(['broker_server.name']);

            $grid->actions( function($actions) {
                $actions->disableEdit();
                $actions->disableDelete();

                if(!$actions->row->is_default) {
                    $actions->append(new MarkBrokerServerAsDefault());
                }
            });
            $grid->disableRowSelector();
        });
    }

    protected function form()
    {
        return new Form(new UserBrokerServer(), function (Form $form) {
            $form->hidden('user_id')->value(Admin::user()->id);
            $form->hidden('broker_server_id')->value(0);

            $form->file('srv_file_path')->required()->autoUpload()->help(___('help_srv_file'));

            $form->switch('enabled')->default(1);

            $form->number('gmt_offset')->default(0);
            $form->text('suffix')->help(___('help_suffix'));

            $form->text('default_group')->default(config('funded.mt4_default_group'));

            $form->saving(function (Form $form) {

                if (!is_null($form->srv_file_path)) {

                    $storage = Storage::disk(config('admin.upload.disk'));

                    if(Str::contains($form->srv_file_path, '.srv', true)) {
                        $type = MetatraderType::V4;
                    } else if(Str::contains($form->srv_file_path, '.dat', true)) {
                        $type = MetatraderType::V5;
                    } else {
                        return $form->response()
                            ->error(trans('admin.update_failed'));
                    }

                    $data = $storage->get($form->srv_file_path);
                    $storage->delete($form->srv_file_path);

                    if($type == MetatraderType::V4)
                        $name = Str::of($form->srv_file_path)->basename('.srv');
                    else {
                        $json = MT5TerminalApi::parseDat($data);
                        $mt5Server = json_decode($json);
                        $name = $mt5Server->name;
                    }

                    BrokerServer::deleteByName($name);

                    $brokerServer = new BrokerServer;

                    $brokerServer->suffix = $form->suffix;
                    $brokerServer->name = $name;
                    $brokerServer->type = $type;
                    $brokerServer->srv_file = $data;
                    $brokerServer->is_updated_or_new = 1;
                    if($type == MetatraderType::V5)
                        $brokerServer->status = BrokerServerStatus::ACTIVE;
                    $brokerServer->save();

                    $uBroker = new UserBrokerServer;

                    $isDefaultExists = UserBrokerServer::where('is_default', 1)->whereUserId(Admin::user()->id)->exists();

                    if(!$isDefaultExists)
                        $uBroker->is_default = 1;

                    $uBroker->broker_server_id = $brokerServer->id;
                    $uBroker->enabled = $form->enabled;
                    $uBroker->user_id = $form->user_id;
                    $uBroker->default_group = $form->default_group;

                    $uBroker->save();

                    return $form->response()
                        ->success(trans('admin.update_succeeded'))
                        ->redirect($form->resource(0));
                }
            });
        });
    }

}