<?php

use Illuminate\Support\Str;
return [

    /*
    |--------------------------------------------------------------------------
    | Horizon Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Horizon will be accessible from. If this
    | setting is null, Horizon will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => env('HORIZON_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Horizon Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Horizon will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('HORIZON_PATH', 'horizon'),

    /*
    |--------------------------------------------------------------------------
    | Horizon Redis Connection
    |--------------------------------------------------------------------------
    |
    | This is the name of the Redis connection where Horizon will store the
    | meta information required for it to function. It includes the list
    | of supervisors, failed jobs, job metrics, and other information.
    |
    */

    'use' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Horizon Redis Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix will be used when storing all Horizon data in Redis. You
    | may modify the prefix when you are running multiple installations
    | of Horizon on the same server so that they don't have problems.
    |
    */

    'prefix' => env(
        'HORIZON_PREFIX',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_horizon:'
    ),


    /*
    |--------------------------------------------------------------------------
    | Horizon Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Horizon route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => ['web','admin'],

    /*
    |--------------------------------------------------------------------------
    | Queue Wait Time Thresholds
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure when the LongWaitDetected event
    | will be fired. Every connection / queue combination may have its
    | own, unique threshold (in seconds) before this event is fired.
    |
    */

    'waits' => [
        'redis:default' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Job Trimming Times
    |--------------------------------------------------------------------------
    |
    | Here you can configure for how long (in minutes) you desire Horizon to
    | persist the recent and failed jobs. Typically, recent jobs are kept
    | for one hour while all failed jobs are stored for an entire week.
    |
    */

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    /*
    |--------------------------------------------------------------------------
    | Metrics
    |--------------------------------------------------------------------------
    |
    | Here you can configure how many snapshots should be kept to display in
    | the metrics graph. This will get used in combination with Horizon's
    | `horizon:snapshot` schedule to define how long to retain metrics.
    |
    */

    'metrics' => [
        'trim_snapshots' => [
            'job' => 24,
            'queue' => 24,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fast Termination
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Horizon's "terminate" command will not
    | wait on all of the workers to terminate unless the --wait option
    | is provided. Fast termination can shorten deployment delay by
    | allowing a new instance of Horizon to start while the last
    | instance will continue to terminate each of its workers.
    |
    */

    'fast_termination' => false,

    /*
    |--------------------------------------------------------------------------
    | Memory Limit (MB)
    |--------------------------------------------------------------------------
    |
    | This value describes the maximum amount of memory the Horizon master
    | supervisor may consume before it is terminated and restarted. For
    | configuring these limits on your workers, see the next section.
    |
    */

    'memory_limit' => 64,

    /*
    |--------------------------------------------------------------------------
    | Queue Worker Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define the queue worker settings used by your application
    | in all environments. These supervisors and settings handle all your
    | queued jobs and will be provisioned by Horizon during deployment.
    |
    */

    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'maxProcesses' => 1,
            'memory' => 128,
            'tries' => 1,
            'nice' => 0,
        ],
    ],

    'environments' => [
        'production' => [
            'supervisor-1' => [
                'maxProcesses' => 10,
                'balanceMaxShift' => 1,
                'balanceCooldown' => 3,
            ],
        ],
        'defender' => [
            'default' => [
                'connection' => 'redis',
                'queue' => ['default','social','removing'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ]
        ],
        'robert' => [
            'default' => [
                'connection' => 'redis',
                'queue' => ['default','social','removing','orders', 'accounts_stat'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            'accounts' => [
                'connection' => 'redis',
                'queue' => ['accounts'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            '178.162.218.27' => [
                'connection' => 'redis',
                'queue' => ['178.162.218.27'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            '199.223.252.150' => [
                'connection' => 'redis',
                'queue' => ['199.223.252.150'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ]
        ],
        'funded' => [
            'default' => [
                'connection' => 'redis',
                'queue' => ['default','social','removing','performances', 'orders', 'accounts'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
                'timeout' => 5 * 60
            ],
            'apis' => [
                'connection' => 'redis',
                'queue' => ['199.223.252.150', '65.108.143.97'],
                'balance' => 'simple',
                'processes' => 1,
                'tries' => 5,
            ],
            'accounts_stat' => [
                'connection' => 'redis',
                'queue' => ['accounts_stat'],
                'balance' => 'simple',
                'processes' => 5,
                'tries' => 5,
            ]
        ],
        'copier' => [
            'default' => [
                'connection' => 'redis',
                'queue' => ['default','social','removing','performances', 'orders'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
                'timeout' => 5 * 60
            ],
            'accounts' => [
                'connection' => 'redis',
                'queue' => ['accounts'],
                'balance' => 'simple',
                'processes' => 15,
                'tries' => 5,
            ],
            'accounts_stat' => [
                'connection' => 'redis',
                'queue' => ['accounts_stat'],
                'balance' => 'simple',
                'processes' => 5,
                'tries' => 5,
            ]
        ],
        'forme' => [
            'default' => [
                'connection' => 'redis',
                'queue' => ['default','social','removing','performances', 'orders'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
                'timeout' => 5 * 60
            ],
            'accounts' => [
                'connection' => 'redis',
                'queue' => ['accounts'],
                'balance' => 'simple',
                'processes' => 1,
                'tries' => 5,
            ],
            'accounts_stat' => [
                'connection' => 'redis',
                'queue' => ['accounts_stat'],
                'balance' => 'simple',
                'processes' => 5,
                'tries' => 5,
            ],
            '199.16.54.10' => [
                'connection' => 'redis',
                'queue' => ['199.16.54.10'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            '65.108.143.97' => [
                'connection' => 'redis',
                'queue' => ['65.108.143.97'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            '163.182.175.82' => [
                'connection' => 'redis',
                'queue' => ['163.182.175.82'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ],
            '209.240.98.130' => [
                'connection' => 'redis',
                'queue' => ['209.240.98.130'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 5,
            ]
        ],
    ],
];
