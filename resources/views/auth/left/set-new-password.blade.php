<style>
    .login-box {
        margin-top: -10rem;
        padding: 5px;
    }
    .login-card-body {
        padding: 1.5rem 1.8rem 1.6rem;
    }
    .card, .card-body {
        border-radius: .25rem
    }
    .login-btn {
        padding-left: 2rem!important;;
        padding-right: 1.5rem!important;
    }
    .content {
        overflow-x: hidden;
    }
    .form-group .control-label {
        text-align: left;
    }
</style>

<div class="login-page bg-40">
    <div class="login-box">
        <div class="login-logo mb-2">
            {{ config('admin.name') }}
        </div>
        <div class="card">
            <div class="card-body login-card-body shadow-100">
                <p class="login-box-msg mt-1 mb-1"><?php if( !empty(session()->get('status'))) echo session()->get('status'); else echo __('admin.set_new_password'); ?></p>

                <form id="login-form" method="POST" action="{{ admin_url('set-new-password') }}">

                    <input type="hidden" name="token" value="{{ $token }}"/>

                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                        <input
                                type="text"
                                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                name="username"
                                placeholder="{{ trans('admin.email') }}"
                                value=""
                                required
                                autofocus
                        >

                        <div class="form-control-position">
                            <i class="feather icon-at-sign"></i>
                        </div>

                        <label for="username">{{ trans('admin.email') }}</label>

                        <div class="help-block with-errors"></div>
                        @if($errors->has('username'))
                            <span class="invalid-feedback text-danger" role="alert">
                                            @foreach($errors->get('username') as $message)
                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                @endforeach
                                        </span>
                        @endif
                    </fieldset>

                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                        <input
                                minlength="5"
                                maxlength="20"
                                id="password"
                                type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                name="password"
                                placeholder="{{ trans('admin.password') }}"
                                required
                                value=""
                                autocomplete="current-password"
                        >

                        <div class="form-control-position">
                            <i class="feather icon-lock"></i>
                        </div>
                        <label for="password">{{ trans('admin.password') }}</label>

                        <div class="help-block with-errors"></div>
                        @if($errors->has('password'))
                            <span class="invalid-feedback text-danger" role="alert">
                                            @foreach($errors->get('password') as $message)
                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                @endforeach
                                            </span>
                        @endif

                    </fieldset>

                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                        <input
                                minlength="5"
                                maxlength="20"
                                id="password_confirmation"
                                type="password"
                                class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                name="password_confirmation"
                                placeholder="{{ trans('admin.password_confirmation') }}"
                                required
                                value=""
                        >

                        <div class="form-control-position">
                            <i class="feather icon-lock"></i>
                        </div>
                        <label for="password_confirmation">{{ trans('admin.password_confirmation') }}</label>

                        <div class="help-block with-errors"></div>
                        @if($errors->has('password_confirmation'))
                            <span class="invalid-feedback text-danger" role="alert">
                                            @foreach($errors->get('password_confirmation') as $message)
                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                @endforeach
                                            </span>
                        @endif

                    </fieldset>
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary float-right login-btn">
                                {{ __('admin.set_password') }}
                                &nbsp;
                                <i class="feather icon-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-1 mb-1 text-center">Remembered password? <a href="{{ admin_url('auth/login') }}">Sign In</a></p>
            </div>
        </div>
    </div>
</div>
<script>
Dcat.ready(function () {
    $('#login-form').form({
        validate: true,
    });
});
</script>
