<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\CaptchaTrait;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Traits\HasFormResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use CaptchaTrait;
    use HasFormResponse;

    public function reset(Content $content, $token) {
        if ($this->guard()->check()) {
            return redirect($this->getRedirectPath());
        }

        $view = 'auth.'.config('admin.login-layout').'.set-new-password';

        return $content->full()->body(view($view)->with(['token' => $token]));

        //return view('auth.reset-password', ['token' => $token]);
    }

    public function show(Content $content)
    {
        if ($this->guard()->check()) {
            return redirect($this->getRedirectPath());
        }
        $view = 'auth.'.config('admin.login-layout').'.reset-password';
        return $content->full()->body(view($view));
    }

    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('username', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if( $status === Password::PASSWORD_RESET ) {
            admin_toastr(__($status));
            return $this->response()->redirect('auth/login')->with('status', __($status));
        } else {
            return $this->response()->error(__($status))->with(['status' => __($status)])->refresh()->send();
        }

        // return $status === Password::RESET_LINK_SENT
        //     ? $this->response()->success(__($status))->with(['status' => __($status)])->refresh()->send()
        //     : $this->response()->error(__($status))->with(['status' => __($status)])->refresh()->send();
    }



    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $status = Password::sendResetLink(
            $request->only('username')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->response()->success(__($status))->with(['status' => __($status)])->refresh()->send()
            : $this->response()->error(__($status))->with(['status' => __($status)])->refresh()->send();

        // return $this->response()
        //     ->success(trans('admin.reset_successful'))
        //     ->with('status', __('admin.check_email_reset'))
        //     ->refresh()
        //     ->send();
    }

    protected function validator(array $data)
    {
        if (!config('registration.recaptch_enabled')) {
            $data['captcha'] = true;
        } else {
            $data['captcha'] = $this->captchaCheck();
        }

        return Validator::make($data,
            [
                'username'                 => 'required|email|max:255',
                'g-recaptcha-response'  => '',
                'captcha'               => 'required|min:1',
            ],
            [
                'username.required'                => trans('auth.emailRequired'),
                'g-recaptcha-response.required' => trans('auth.captchaRequire'),
                'captcha.min'                   => trans('auth.CaptchaWrong'),
            ]
        );
    }

    protected function guard()
    {
        return Admin::guard();
    }

}