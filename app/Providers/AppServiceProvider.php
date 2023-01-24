<?php

namespace App\Providers;

use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        if( config('funded.log_sql_queries')) {
            Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
                Log::debug($query->sql . ' - ' . serialize($query->bindings));
            });
        }

        $this->app->bind('custom.mailer', function ($app, $parameters) {
            $from_email = $parameters['from_email'];
            $from_name = $parameters['from_name'];

            $transport = Mail::createSymfonyTransport($parameters);

            $mailer = new Mailer('custom.mailer', $app->get('view'), $transport, $app->get('events'));
            $mailer->alwaysFrom($from_email, $from_name);
            $mailer->alwaysReplyTo($from_email, $from_name);

            return $mailer;
        });
    }
}