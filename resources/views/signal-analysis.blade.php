<div class="content" style="padding: 20px;">
    <div class="content-header">
        <a href="{{ admin_url('/') }}" class="navbar-brand waves-effect waves-light">
            <span class="logo-lg">
                <img src="/storage/{!! config('admin.logo-image') !!}">
                <h1>
                    {!! $header !!}
                    <small>{!! $description !!}</small>
                </h1>
            </span>
        </a>

    </div>
    <div class="content-body" id="app" style="padding: 10px">
        {!! $content !!}
    </div>
</div>