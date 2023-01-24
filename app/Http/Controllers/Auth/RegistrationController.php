<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Traits\HasFormResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{

    use ActivationTrait;
    use CaptchaTrait;
    use HasFormResponse;

    public function show(Content $content)
    {
        if ($this->guard()->check()) {
            return redirect($this->getRedirectPath());
        }
        $view = 'auth.'.config('admin.login-layout').'.registration';
        return $content->full()->body(view($view));
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $user->notify(new UserRegisteredNotification());
        event(new Registered($user));

        //$this->guard()->attempt([$user->]);

        //$this->guard()->login($user);

        //$request->session()->regenerate();

        return $this->response()
            ->success(trans('admin.register_successful'))
            ->with('registered', __('admin.check_email_activation'))
            ->refresh()
            ->send();
    }

    protected function validator(array $data)
    {
        if (!config('admin.recaptch-enabled')) {
            $data['captcha'] = true;
        } else {
            $data['captcha'] = $this->captchaCheck();
        }

        return Validator::make($data,
            [
                'name'                  => 'required|max:255',
                'email'                 => 'required|email|max:255|unique:admin_users',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'g-recaptcha-response'  => '',
                'captcha'               => 'required|min:1',
            ],
            [
                'name.required'                 => trans('auth.userNameRequired'),
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
                'g-recaptcha-response.required' => trans('auth.captchaRequire'),
                'captcha.min'                   => trans('auth.CaptchaWrong'),
            ]
        );
    }

    protected function create(array $data) : User
    {
        $user = User::create([
                'name'              => $data['name'],
                'username'          => $data['email'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'api_token'         => Str::random(12),
                'signup_ip'         => \Request::getClientIp(),
                'activated'         => !config('registration.activation_enabled'),
                'creator_id'        => Admin::domain()->manager_id,
                'manager_id'        => Admin::domain()->manager_id,
            ]);

        $this->initiateEmailActivation($user);

        return $user;
    }

    protected function guard()
    {
        return Admin::guard();
    }

    protected function getRedirectPath()
    {
        return admin_url('/');
    }
}