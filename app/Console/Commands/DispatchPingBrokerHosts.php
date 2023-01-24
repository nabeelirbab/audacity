<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Jobs\PingBrokerHost;
use App\Models\BrokerServerHost;
use Carbon\Carbon;

class DispatchPingBrokerHosts extends BaseCommand
{
    protected $signature = 'brokerservers:ping_hosts {--count=}';

    protected $description = 'Dispatch to ping broker server hosts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        try {
            $diff = 30; //mins

            $count = $this->option('count');

            if(empty($count))
                $count = 50;

            $hosts = BrokerServerHost
                ::where('updated_at','<',Carbon::now()->subMinutes($diff))
                ->where('ping','>=',0)
                ->take($count)
                ->pluck('id');

            $hosts->each(function($id){
                PingBrokerHost::dispatch($id);
            });

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            //echo $e->getMessage();
        }
    }
}
