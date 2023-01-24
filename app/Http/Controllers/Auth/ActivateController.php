<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\User;
use App\Notifications\UserActivatedNotification;
use Dcat\Admin\Admin;
use Illuminate\Support\Facades\Log;

class ActivateController extends Controller
{

    public function activate($token)
    {
        $activation = Activation::where('token', $token)->get()->first();

        if (empty($activation)) {
            Log::error('Registered user attempted to activate with an invalid token: '.$token);

            return redirect('/login')
                ->with('message', trans('auth.invalidToken'));
        }

        $user = User::find($activation->user_id);

        if ($user->activated) {
            Log::error('Activated user attempted to visit with token'.$token.'. ', [$user]);

            Admin::guard()->login($user);

            return redirect('/');
        }

        $user->activated = true;
        $user->signup_confirmation_ip = \Request::getClientIp();

        $user->Save();

        Activation::where('user_id', $user->id)->delete();

        $user->notify(new UserActivatedNotification($user, $user->manager_id));
        Log::info('Registered user successfully activated. ', [$user]);

        Admin::guard()->login($user);

        admin_toastr(__('admin.activated_successful'));
        return redirect('/');
    }

}