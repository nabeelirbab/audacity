<?php

use App\Enums\EnvType;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Dcat\Admin\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

if(config('funded.env_type') == EnvType::COPIER->value ) {
        Route::group([
                'prefix'        => config('admin.route.prefix'),
                'namespace'     => config('admin.route.namespace'),
                'middleware'    => config('admin.route.middleware'),
        ], function (Router $router) {

                $router->resources([
                //Copier
                        'my-accounts' => Copier\MyAccountController::class,
                ]);
        });
}

if(config('funded.env_type') == EnvType::PERFORMANCES->value) {
        Route::group([
                'prefix'        => config('admin.route.prefix'),
                'namespace'     => config('admin.route.namespace'),
                'middleware'    => config('admin.route.middleware'),
        ], function (Router $router) {

                $router->resources([
                        'my-accounts' => Performance\MyAccountController::class,
                ]);
        });

        Route::prefix(config('admin.route.prefix'))
                ->middleware(config('admin.route.middleware'))
                ->get('trading-objectives/view/{slug}', 'App\Http\Controllers\Performance\PerformanceMetricsController@internal');
}

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('empty');

    $router->get('helpdesk/{ticketId}/comment', 'MyHelpdeskTicketController@commentShow');
    $router->post('helpdesk/{ticketId}/comment', 'MyHelpdeskTicketController@commentStore');

    $router->get('tickets/{ticketId}/comment', 'HelpdeskTicketController@commentShow');
    $router->post('tickets/{ticketId}/comment', 'HelpdeskTicketController@commentStore');

    $router->get('email-settings', 'ManagerMailSettingController@index');
    $router->put('email-settings', 'ManagerMailSettingController@store');
    $router->post('email-settings', 'ManagerMailSettingController@store');

    $router->get('terminal/artisan', 'TerminalController@artisan');
    $router->post('terminal/artisan', 'TerminalController@runArtisan');

    $router->resources([

        'clients' => UserController::class,
        'accounts' => AccountController::class,

        'trading-objectives' => TradingObjectiveController::class,
        'my-trading-objectives' => MyTradingObjectiveController::class,

        //Base
        'access-tokens' => AccessTokenController::class,
        'system-dashboard' => SystemDashboardController::class,
        'brokers' => BrokerController::class,
        'managers' => MyManagerController::class,
        'broker-servers' => UserBrokerServerController::class,
        'apis' => ApiServerController::class,
        'orders' => OrderController::class,
        'apis-stat' => ApiServerStatController::class,
        'my-trades' => MyTradeController::class,
        'tags' => TagController::class,
        'email-templates' => EmailTemplateController::class,
        'video-posts' => VideoPostController::class,
        'my-video-posts' => MyVideoPostController::class,
        'system-logs' => SystemLogController::class,
        'notifications' => NotificationController::class,
        'my-notifications' => MyNotificationController::class,
        'translator' => TranslatorController::class,
        'authentication-log' => AuthenticationLogController::class,
        'routes' => RouteController::class,
        'zendesk' => ZendeskController::class,
        'tickets' => HelpdeskTicketController::class,
        'helpdesk' => MyHelpdeskTicketController::class,
// ---------------------------------

        // Performances

        'challenges' => Performance\ChallengeController::class,
        'my-challenges' => Performance\MyChallengeController::class,
        'plans' => Performance\PerformancePlanController::class,

// ---------------------------------

        // Copier
        'my-copiers' => Copier\MySignalFollowerController::class,
        'followers' => Copier\SignalFollowerController::class,
        'signals' => Copier\SignalController::class,
        'comparer' => Copier\CopiedOrdersComparerController::class,
        'followers-overview' => Copier\SignalFollowerOverviewController::class,

// ---------------------------------

        // MT4 Manager
        'broker-managers' => BrokerManagerController::class,

//----------------------------------
        // Portfolios
        'strategies' => StrategyController::class,
        'portfolios' => PortfolioController::class,
        'strategyorders' => StrategyOrderController::class,
        'portfolio-trades' => PortfolioOrderController::class,
        'my-strategies' => MyStrategyController::class,
        'my-portfolios' => MyPortfolioController::class,

//----------------------------------
        // Experts
        'account-templates' => AccountExpertTemplateController::class,
        'expert-subscriptions' => ExpertSubscriptionController::class,
        'mt4-files' => MT4FileController::class,
        'experts' => ExpertController::class,

//----------------------------------

        // Defender
        'dusers' => User4DefenderController::class,
        'products' => ProductController::class,
        'poptions' => ProductOptionController::class,
        'pfiles' => ProductFileController::class,
        'pmaccounts' => ProductMemberAccountController::class,
        'campaigns' => CampaignController::class,
        'members' => MemberController::class,
//        'cmembers' => CampaignMemberController::class,
        'daccounts' => Account4DefenderController::class,
        'presets' => LicensePresetController::class,

//----------------------------------

    ]);

    Route::get('/reset-password', [ResetPasswordController::class, 'show']);
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);

    Route::get('/register', [RegistrationController::class, 'show'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store']);

    $router->get('account-analysis/{accountNumber}', 'AccountAnalysisController@internal');

    $router->get('user/impersonate/{id}', 'UserController@impersonate');
    $router->get('user/deimpersonate', 'UserController@deimpersonate');

    $router->post('caccounts/add2copier', 'AccountController@add2copier');

    $router->post('telegramsubscriptions/test', 'TelegramSubscriptionController@test');
    $router->post('emailsubscriptions/test', 'EmailSubscriptionController@test');
    $router->post('email-templates/test', 'EmailTemplateController@test');
    $router->post('email-settings/test', 'ManagerMailSettingController@test');

    $router->get('client-area', 'UserController@clientArea');
    $router->get('user/setting', 'UserController@getSetting')->name('admin.setting');
    $router->put('user/setting', 'UserController@putSetting');

    $router->get('myemailsubscriptions/subscribe/{id}', 'MyEmailSubscriptionController@subscribe');

    $router->get('api/mycopiers/account', 'MyCopierSubscriptionDestAccountController@account');
    $router->post('accounts/update_status', 'AccountController@update_status');
    $router->post('accounts/move_to', 'AccountController@move_to');
});

Route::get('/debug-sentry', function () {
        throw new Exception('My first Sentry error2!');
    });