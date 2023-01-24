<?php

use App\Enums\EnvType;

return [

    'log_sql_queries' => env('LOG_SQL_QUERIES', false),

    /*
    |--------------------------------------------------------------------------
    | JFX mode
    |--------------------------------------------------------------------------
    |
    | This option controls the default value for copier mode
    |
    | Supported: "0 - sync disabled", "1 - debug enabled", "2 - calc stat", "4 - mysql skip own",
    |            "8 - mysql watcher", "16 - close when", "32 - load EA"
    |
    */
    'jfx_mode' => env('JFX_MODE', 0),

    /*
    |--------------------------------------------------------------------------
    | Env Type
    |--------------------------------------------------------------------------
    |
    | This option controls the value for the project env
    |
    | Supported: "1 - copier", "2 - performances"
    |
    */
    'env_type' => env('ENV_TYPE', EnvType::COPIER),

    'ws_host' => env('WS_HOST', '127.0.0.1'),
    'db_host' => env('FUNDED_DB_HOST', '127.0.0.1'),
    'monitor_last_orders' => env('MONITOR_LAST_ORDERS', "20"),

    'max_accounts_def' => env('MAX_ACCOUNTS_DEF', 0),

    'url_terminal_api' => env('URL_TERMINAL_API', 'terminal-api.dev4traders.com:9000'),

    'notify_account_invalid' => env('NTF_ACCOUNT_INVALID'),

    'active_minites' => env('ACTIVE_MINUTES', 10),
    'ping_broker_hosts' => env('PING_BROKER_HOSTS', false),
    'api_single_thread' => env('API_SINGLE_THREAD', false),
    'delay_ms' => env('FUNDED_DELAY_MS', 1000),
    'restart_invalid_every_minutes' => env('RESTART_INVALID_EVERY_MINUTES', null),
    'mt4_default_group' => env('MT4_DEFAULT_GROUP', 'demoforex'),
];
