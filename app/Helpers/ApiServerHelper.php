<?php

namespace App\Helpers;

use App\Models\ApiServer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ApiServerHelper
{
    public static function RefreshHorizonHosts() {

        try {
            $apiHosts = ApiServer::enabled()->distinct()->pluck('ip')->toArray();
            Config::write('horizon.environments.funded.apis.queue', $apiHosts);

            Artisan::call('config:cache');
            exec('cd '.base_path().' && sudo php artisan horizon:terminate');
        } catch(\Exception $e) {
            Log::critical('failed to RefreshHorizonHosts', ['ex' => $e->getMessage()]);
            //echo $e->getMessage();
        }


    }
}
