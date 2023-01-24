<?php

namespace App\Jobs;

use App\Enums\BrokerServerHostStatus;
use App\Jobs\ShouldQueueBase;
use App\Models\BrokerServerHost;

final class PingBrokerHost extends ShouldQueueBase
{
    private $hostId;

    protected $signature = 'brokerserverhost:ping';

    public $timeout = 120;
    public $tries = 5;

    public function __construct(int $id)
    {
        $this->hostId = $id;
    }

    function pingDomain($domain, $port){
        $starttime = microtime(true);
        $file      = fsockopen ($domain, $port, $errno, $errstr, 10);
        $stoptime  = microtime(true);
        $status    = 0;

        if (!$file) $status = 0;  // Site is down
        else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status;
    }

    public function handle()
    {
        $host = BrokerServerHost::with('brokerServer')->find($this->hostId);

        if(is_null($host) || is_null($host->brokerServer))
            return;

        try {
            $ping = $this->pingDomain($host->host, $host->port);

            $host->ping = $ping;
            $host->status = BrokerServerHostStatus::ONLINE;
        } catch (\Exception $e) {
            $host->ping = -1;
            $host->status = BrokerServerHostStatus::OFFLINE;
        }

        $host->save();
    }

    public function failed($exception)
    {
        $this->critical($exception);
    }

    public function tags()
    {
        return ['brokerservers', 'brokerserverhost:'.$this->hostId];
    }
}