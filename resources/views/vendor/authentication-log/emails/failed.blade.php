@component('mail::message')
# @lang('Hello')!

@lang('There has been a failed login attempt to your :app account.', ['app' => config('app.name')])

> **@lang('Email'):** {{ $account->email }}<br/>
> **@lang('Time'):** {{ $time->toCookieString() }}<br/>
> **@lang('IP Address'):** {{ $ipAddress }}<br/>
> **@lang('Browser'):** {{ $browser }}<br/>
@if ($location && $location['default'] === false)
> **@lang('Location'):** {{ $location['city'] ?? 'Unknown City' }}, {{ $location['country_name'], 'Unknown Country' }}
@endif

@lang('If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.')

@lang('Regards'),<br/>
{{ config('app.name') }}
@endcomponent
