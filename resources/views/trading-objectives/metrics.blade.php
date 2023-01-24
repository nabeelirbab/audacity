<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>@if(! empty($header)){{ $header }} | @endif {{ Dcat\Admin\Admin::title() }}</title>

    @if(! config('admin.disable_no_referrer_meta'))
        <meta name="referrer" content="no-referrer"/>
    @endif

    @if(! empty($favicon = Dcat\Admin\Admin::favicon()))
        <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! Dcat\Admin\Admin::asset()->headerJsToHtml() !!}

    {!! Dcat\Admin\Admin::asset()->cssToHtml() !!}
</head>

<body class="dcat-admin-body full-page">

<script>
    var Dcat = CreateDcat({!! Dcat\Admin\Admin::jsVariables() !!});
</script>

<div class="content" style="padding: 20px;">
    <div class="content-header">
        <a href="{{ admin_url('/') }}" class="navbar-brand waves-effect waves-light">
            <span class="logo-lg"><img style="max-width: 45px; max-height: 40px;" src="/storage/{!! config('admin.logo-image') !!}"></span>
        </a>
    </div>
    <div class="content-body" id="app" style="padding: 10px">
        {!! $content !!}
    </div>

    {!! Dcat\Admin\Admin::asset()->scriptToHtml() !!}
</div>

{!! Dcat\Admin\Admin::asset()->jsToHtml() !!}

<script>Dcat.boot();</script>

</body>
</html>