<style>
    .row {
        margin: 0;
    }
    .col-md-12,
    .col-md-3 {
        padding: 0;
    }
    @media screen and (min-width: 1000px) and (max-width: 1150px) {
        .col-lg-3,
        .col-lg-9 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    @media screen and (min-width: 1151px) and (max-width: 1300px) {
        .col-lg-3 {
            flex: 0 0 40%;
            max-width: 40%;
        }
        .col-lg-9 {
            flex: 0 0 60%;
            max-width: 60%;
        }
    }
    @media screen and (min-width: 1301px) and (max-width: 1700px) {
        .col-lg-3 {
            flex: 0 0 35%;
            max-width: 35%;
        }
        .col-lg-9 {
            flex: 0 0 65%;
            max-width: 65%;
        }
    }

    .login-page {
        height: auto;
    }
    .login-main {
        position: relative;
        display: flex;
        min-height: 100vh;
        flex-direction: row;
        align-items: stretch;
        margin: 0;
    }

    .login-main .login-page {
        background-color: #fff;
    }

    .login-main .card {
        box-shadow: none;
    }

    .login-main .auth-brand {
        margin: 4rem 0 4rem;
        font-size: 26px;
        width: 325px;
    }

    @media (max-width: 576px) {
        .login-main .auth-brand {
            width: 90%;
            margin-left: 24px
        }
    }

    .login-main .login-logo {
        font-size: 2.1rem;
        font-weight: 300;
        margin-bottom: 0.9rem;
        text-align: left;
        margin-left: 20px;
    }

    .login-main .login-box-msg {
        margin: 0;
        padding: 0 0 20px;
        font-size: 0.9rem;
        font-weight: 400;
        text-align: left;
    }

    .login-main .btn {
        width: 100%;
    }

    .login-page-right {
        padding: 6rem 3rem;
        flex: 1;
        position: relative;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.3);
        text-align: center !important;
        background: url( /storage/{{ config('admin.login-image') }} ) center;
        background-size: cover;
    }

    .login-description {
        position: absolute;
        margin: 0 auto;
        padding: 0 1.75rem;
        bottom: 3rem;
        left: 0;
        right: 0;
    }

    .content-front {
        position: absolute;
        left: 0;
        right: 0;
        height: 100vh;
        background: rgba(0,0,0,.1);
        margin-top: -6rem;
    }

    body.dark-mode .content-front {
        background: rgba(0,0,0,.3);
    }

    body.dark-mode .auth-brand {
        color: #cacbd6
    }

    .auth-brand img {
        display: inline-block;
        max-width: 45px;
        max-height: 40px;
        margin-top: -1px;
    }
</style>

<div class="row login-main">
    <div class="col-lg-3 col-12 bg-white">
        <div class="login-page">
            <div class="auth-brand text-lg-left">
                                <span class="logo-lg">
                                    <img style="max-width: 45px; max-height: 40px; margin-top: -1px;" src="/storage/{!! config('admin.logo-image') !!}">
                                    <span>{!! config('admin.name') !!}</span>
                                </span>
            </div>

            <div class="login-box">
                <div class="login-logo mb-2">
                    <p class="login-box-msg mt-1 mb-1"><?php if( !empty(session()->get('registered'))) echo session()->get('registered'); else echo __('admin.register'); ?></p>
                </div>
                <div class="card">
                    <div class="card-body login-card-body">

                        <form id="login-form" method="POST" action="/register">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                <input
                                        type="text"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        name="name"
                                        placeholder="{{ trans('admin.name') }}"
                                        value=""
                                        required
                                        autofocus
                                >

                                <div class="form-control-position">
                                    <i class="feather icon-user"></i>
                                </div>

                                <label for="name">{{ trans('admin.name') }}</label>

                                <div class="help-block with-errors"></div>
                                @if($errors->has('name'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                                    @foreach($errors->get('name') as $message)
                                            <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                        @endforeach
                                    </span>
                                @endif
                            </fieldset>

                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                <input
                                        type="text"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        name="email"
                                        placeholder="{{ trans('admin.email') }}"
                                        value=""
                                        required
                                        autofocus
                                >

                                <div class="form-control-position">
                                    <i class="feather icon-at-sign"></i>
                                </div>

                                <label for="email">{{ trans('admin.email') }}</label>

                                <div class="help-block with-errors"></div>
                                @if($errors->has('email'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                                    @foreach($errors->get('email') as $message)
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
                            @if(config('admin.recaptch-enabled'))
                                <fieldset class="form-group">
                                    <div class="g-recaptcha" data-sitekey="{!! config('admin.recaptch-site') !!}"></div>
                                </fieldset>
                            @endif
                            <button type="submit" class="btn btn-primary float-right login-btn">

                                {{ __('admin.register') }}
                                &nbsp;
                                <i class="feather icon-arrow-right"></i>
                            </button>
                        </form>
                        <p class="text-center">Already have an account? <a href="/auth/login">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-9 col-12 login-page-right">
        <div class="content-front"></div>
        <div class="login-description">
            <p class="lead">
            </p>
        </div>
    </div>
</div>
@if(config('admin.recaptch-enabled'))
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endif

<script>
    Dcat.ready(function () {
        $('#login-form').form({
            validate: true
        });
    });
</script>