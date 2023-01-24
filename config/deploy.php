<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default deployment strategy
    |--------------------------------------------------------------------------
    |
    | This option defines which deployment strategy to use by default on all
    | of your hosts. Laravel Deployer provides some strategies out-of-box
    | for you to choose from explained in detail in the documentation.
    |
    | Supported: 'basic', 'firstdeploy', 'local', 'pull'.
    |
    */

    'default' => 'basic',

    /*
    |--------------------------------------------------------------------------
    | Custom deployment strategies
    |--------------------------------------------------------------------------
    |
    | Here, you can easily set up new custom strategies as a list of tasks.
    | Any key of this array are supported in the `default` option above.
    | Any key matching Laravel Deployer's strategies overrides them.
    |
    */

    'strategies' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    | Hooks let you customize your deployments conveniently by pushing tasks
    | into strategic places of your deployment flow. Each of the official
    | strategies invoke hooks in different ways to implement their logic.
    |
    */

    'hooks' => [
        // Right before we start deploying.
        'start' => [
            //
        ],

        // Code and composer vendors are ready but nothing is built.
        'build' => [
            //
        ],

        // Deployment is done but not live yet (before symlink)
        'ready' => [
            'artisan:storage:link',
            'artisan:view:clear',
            'artisan:cache:clear',
            'artisan:config:cache',
        ],

        // Deployment is done and live
        'done' => [
            'fpm:reload'
        ],

        'rollback' => [
            'fpm:reload'
        ],

        // Deployment succeeded.
        'success' => [
        ],

        // Deployment failed.
        'fail' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Deployment options
    |--------------------------------------------------------------------------
    |
    | Options follow a simple key/value structure and are used within tasks
    | to make them more configurable and reusable. You can use options to
    | configure existing tasks or to use within your own custom tasks.
    |
    */

    'options' => [
        'application' => env('APP_NAME', 'Funded'),
        'repository' => 'git@github.com:mikha-dev/funded.git',
        'composer_options' => '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest',
        'composer_action' => 'install',
        'php_fpm_service' => 'php7.4-fpm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hosts
    |--------------------------------------------------------------------------
    |
    | Here, you can define any domain or subdomain you want to deploy to.
    | You can provide them with roles and stages to filter them during
    | deployment. Read more about how to configure them in the docs.
    |
    */

    'hosts' => [
        '45.79.190.248' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'cedric'
        ],
        '178.79.169.57' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'tudor'
        ],
        'funded.dev4traders.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'funded'
        ],
        'app.disrupt-financial.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'vernon'
        ],
        'fxtradingempire.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'robert'
        ],
        'tamanager.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'tamanager'
        ],
        'copier.dev4traders.com' => [
            'deploy_path' => '/var/www/html/copier',
            'user' => 'root',
            'stage' => 'copier'
        ],
        'thetradedash.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'dash'
        ],
        'thetradedash.com' => [
            'deploy_path' => '/var/www/html/bitbrytetrade',
            'user' => 'root',
            'stage' => 'bitbrytetrade'
        ],
        '206.189.12.221' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'tanzim'
        ],
        '212.71.233.205' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'james'
        ],
        'forme.dev4traders.com' => [
            'deploy_path' => '/var/www/html/funded',
            'user' => 'root',
            'stage' => 'forme'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localhost
    |--------------------------------------------------------------------------
    |
    | This localhost option give you the ability to deploy directly on your
    | local machine, without needing any SSH connection. You can use the
    | same configurations used by hosts to configure your localhost.
    |
    */

    'localhost' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Include additional Deployer recipes
    |--------------------------------------------------------------------------
    |
    | Here, you can add any third party recipes to provide additional tasks,
    | options and strategies. Therefore, it also allows you to create and
    | include your own recipes to define more complex deployment flows.
    |
    */

    'include' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Use a custom Deployer file
    |--------------------------------------------------------------------------
    |
    | If you know what you are doing and want to take complete control over
    | Deployer's file, you can provide its path here. Note that, without
    | this configuration file, the root's deployer file will be used.
    |
    */

    'custom_deployer_file' => false,

];