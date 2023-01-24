<?php

namespace App\Admin\Controllers;

use App\Enums\AccessTokenAbilityType;
use App\Models\User;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Copyable;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Alert;
use Illuminate\Contracts\View\View;
use Laravel\Sanctum\Sanctum;

class AccessTokenController extends AdminController
{
    protected function grid()
    {
        return new Grid( Sanctum::personalAccessTokenModel(), function (Grid $grid) {

            $grid->wrap(function(View $view) {

                if(session()->has('access_token')) {
                    $alert = new Alert(session('access_token'), ___('alert_copy_token'));
                    return $alert->render().$view->render();
                }

                return $view;
            });

            $grid->model()->where('tokenable_id',Admin::user()->id)->where('tokenable_type', User::class);

            $grid->id();
            $grid->column('name')->editable();
            $grid->column('abilities')->label();

            $grid->column('last_used_at')->display(function($date) {
                if(is_null($date))
                    return ___('never');
                return Carbon::parse($date)->diffForHumans();
            });
            $grid->column('created_at')->dateHuman();
            $grid->column('updated_at')->dateHuman();

            $grid->disableFilterButton();
            $grid->disableRefreshButton();
            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return new Form( Sanctum::personalAccessTokenModel(), function (Form $form) {

            $form->hidden('tokenable_type')->value(User::class);
            $form->hidden('tokenable_id')->value(Admin::user()->id);

            $form->text('name')->required();
            $form->listbox('abilities')->options(AccessTokenAbilityType::map());

            $form->disableViewButton();

            $form->saving(function(Form $form) {

                if($form->isCreating()) {

                    $abilities = collect($form->abilities)->filter(function($ability) {
                        return !is_null($ability);
                    });

                    /** @var \Laravel\Sanctum\NewAccessToken $newToken */
                    $newToken = Admin::user()->createToken($form->name, $abilities->toArray());

                    session()->flash('access_token', $newToken->plainTextToken);

                    return $form->response()->success(__('admin.save_succeeded'))->redirect($form->resource(0));
                }
            });

        });
    }
}