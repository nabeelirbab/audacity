<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';

set('application', 'Funded');
set('repository', 'git@github.com:mikha-dev/funded.git');
set('php_fpm_version', '8.1');
set('update_code_strategy', 'clone');

host('karim-funded')
    ->set('stage', 'karim-funded')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.252.90')
    ->set('deploy_path', '/var/www/html/funded');

host('rupert')
    ->set('stage', 'rupert')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.144.155.229')
    ->set('deploy_path', '/var/www/html/funded');

host('ian3')
    ->set('stage', 'ian3')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.144.188.57')
    ->set('deploy_path', '/var/www/html/copier');

host('karim')
    ->set('stage', 'karim')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '85.159.215.198')
    ->set('deploy_path', '/var/www/html/copier');

host('zee')
    ->set('stage', 'zee')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.202.72')
    ->set('deploy_path', '/var/www/html/funded');

host('salve')
    ->set('stage', 'salve')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '85.159.212.46')
    ->set('deploy_path', '/var/www/html/funded');

host('mdpfunding')
    ->set('stage', 'mdpfunding')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '212.71.238.219')
    ->set('deploy_path', '/var/www/html/mdpfunding');

host('originfunding')
    ->set('stage', 'originfunding')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '185.3.95.170')
    ->set('deploy_path', '/var/www/html/originfunding');
host('originfunding')
    ->set('stage', 'originfunding')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '185.3.95.170')
    ->set('deploy_path', '/var/www/html/originfunding');

host('proforexfunding')
    ->set('stage', 'superfunded')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '109.74.196.230')
    ->set('deploy_path', '/var/www/html/proforexfunding');

host('victor')
    ->set('stage', 'victor')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.144.21.96')
    ->set('deploy_path', '/var/www/html/funded');

host('superfunded')
    ->set('stage', 'superfunded')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '109.74.196.230')
    ->set('deploy_path', '/var/www/html/superfunded');

host('btfxcapital')
    ->set('stage', 'btfxcapital')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.255.28')
    ->set('deploy_path', '/var/www/html/btfxcapital');

host('msolutionff')
    ->set('stage', 'msolutionff')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.255.28')
    ->set('deploy_path', '/var/www/html/msolutionff');

host('fxcapital')
    ->set('stage', 'fxcapital')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.195.206')
    ->set('deploy_path', '/var/www/html/funded');

host('copy')
    ->set('stage', 'copy')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '139.162.195.206')
    ->set('deploy_path', '/var/www/html/copier');

host('rick')
    ->set('stage', 'rick')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', 'cali.desertrising.net')
    ->set('deploy_path', '/home/propchoice/mypropdash.com');

host('symtrade')
    ->set('stage', 'symtrade')
    ->set('repository', 'git@github.com:mikha-dev/funded.git')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', 'panel.symtrade.com')
    ->set('deploy_path', '/var/www/html/funded');

host('copier')
    ->set('stage', 'copier')
    ->set('repository', 'git@github.com:mikha-dev/funded.git')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', 'dev4traders.com')
    ->set('deploy_path', '/var/www/html/copier');

host('funded')
    ->set('stage', 'funded')
    ->set('repository', 'git@github.com:mikha-dev/funded.git')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', 'dev4traders.com')
    ->set('deploy_path', '/var/www/html/funded');

host('thetradedash')
    ->set('stage', 'thetradedash')
    ->set('repository', 'git@github.com:mikha-dev/funded.git')
    ->set('php_fpm_version', '8.2')
    ->set('remote_user', 'root')
    ->set('hostname', 'thetradedash.com')
    ->set('deploy_path', '/var/www/html/thetradedash');

host('forme')
    ->set('stage', 'forme')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', '176.58.110.27')
    ->set('deploy_path', '/var/www/html/funded');

host('liam')
    ->set('stage', 'liam')
    ->set('php_fpm_version', '8.1')
    ->set('remote_user', 'root')
    ->set('hostname', 'liam.dev4traders.com')
    ->set('deploy_path', '/var/www/html/funded');

task('supervisor:restart', function () {
    run('sudo systemctl reload supervisor');
});


task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'deploy:publish',
    'php-fpm:reload',
    'supervisor:restart'
]);

after('deploy:failed', 'deploy:unlock');