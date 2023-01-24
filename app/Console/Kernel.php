<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Laravel\Horizon\Console\HorizonCommand::class,
        //'App\Console\Commands\PingApiServer',
        'App\Console\Commands\StopMovedAccounts',
        'App\Console\Commands\StopDisabledAccounts',
        'App\Console\Commands\StopRemovedAccounts',
        'App\Console\Commands\ReloadAccounts',
        'App\Console\Commands\VerifyAccounts',
        'App\Console\Commands\PingAccounts',
//        'App\Console\Commands\CheckAccountsTradable',
        'App\Console\Commands\UploadUpdatedBrokerServers',
        'App\Console\Commands\RefreshUserBrokerServers',
        'App\Console\Commands\RemoveOldTradeErrors',
        'App\Console\Commands\RemoveExpiredExpertSubscriptions',
//        'App\Console\Commands\MT4Manager\UsersRefresh',
        'App\Console\Commands\RestartInvalidAccounts',
        'App\Console\Commands\RecalcPortfolioStat',
        'App\Console\Commands\RefreshBrokerServerHosts',
        'App\Console\Commands\DispatchPingBrokerHosts',
        'App\Console\Commands\CalculatePerformanceStat',
        'App\Console\Commands\CaclulatePerformanceTarget',
        'App\Console\Commands\RefreshAccountStat',
        'App\Console\Commands\CopierCheckErrorsNotify',
        '\App\Console\Commands\FlushRedis',
        '\App\Console\Commands\GenerateAccountForChallenge',
        '\App\Console\Commands\CheckPerformanceObjectives',
        '\App\Console\Commands\CheckPerformanceStarted',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // $schedule->command('server-monitor:run-checks')
        //     ->withoutOverlapping()
        //     ->everyMinute();

        $schedule->command('log:delete')
            ->everyThreeHours();

        $schedule->command('auth:clear-resets')
            ->everyFifteenMinutes();

        $schedule->command('copier:check_errors')
            ->everyThreeHours();


        $schedule->command('performance:calc_stat')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('performance:calc_target')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('performance:check_started')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('performance:check_objectives')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('performance:generate_account')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('brokerservers:refresh_hosts')
            ->withoutOverlapping()
            ->everyMinute();

        if(config('funded.ping_broker_hosts')) {

            $schedule->command('brokerservers:ping_hosts')
                ->withoutOverlapping()
                ->everyMinute();
        }

        $schedule->command('account:refresh_stat')
            ->withoutOverlapping()
            ->everyTenMinutes();

        $schedule->command('accounts:stopmoved')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('accounts:stopdisabled')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('accounts:stopremoved')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('accounts:reload')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('accounts:verify')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('accounts:ping')
            ->withoutOverlapping()
            ->everyMinute();

        $schedule->command('brokerservers:upload')
            ->withoutOverlapping()
            ->everyMinute();

        // $schedule->command('apiserver:ping')
        //     ->withoutOverlapping()
        //     ->everyMinute();

        $mins = config('funded.restart_invalid_every_minutes');
        if( $mins && $mins != 0 ) {
            $schedule->command('accounts:restart_invalid')
                ->cron("*/$mins * * * *");
        }

        $schedule->command('authentication-log:purge')
            ->daily();

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
