<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\BrokerServerStatus;
use App\Enums\MetatraderType;
use App\Helpers\MT4TerminalApi;
use App\Models\BrokerServer;
use App\Models\BrokerServerHost;

class RefreshBrokerServerHosts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brokerservers:refresh_hosts {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh hosts from srv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        try {

            $count = $this->option('count');

            if(empty($count))
                $count = 100;

            $brokerServers = BrokerServer
                ::api()
                ->unprocessed()
                ->typeOf(MetatraderType::V4)
                ->take($count)
                ->get();

            foreach ($brokerServers as $brokerServer) {
                $name = $brokerServer->name;
                $srvFile = $brokerServer->srv_file;

                try {

                    //$this->info('Parsing srv file', ['name' => $name]);

                    $json = MT4TerminalApi::ParseSrv($srvFile, $name);

                    if ($json) {
                        $data = json_decode($json, true);

                        $brokerServer->main_server_host = $data['mainServer']['host'];
                        $brokerServer->main_server_port = $data['mainServer']['port'];
                        $brokerServer->human_readable = $json;
                        $brokerServer->status = BrokerServerStatus::ACTIVE;
                        $brokerServer->save();

                        BrokerServerHost::whereBrokerServerId($brokerServer->id)->delete();

                        BrokerServerHost::create(
                            [
                                'is_main' => 1,
                                'broker_server_id' => $brokerServer->id,
                                'host' => $data['mainServer']['host'],
                                'port' => $data['mainServer']['port'],
                                'ping' => $data['mainServer']['ping'],
                            ]
                        );

                        foreach($data['slaveServers'] as $slave) {
                            BrokerServerHost::create(
                                [
                                    'broker_server_id' => $brokerServer->id,
                                    'host' => $slave['host'],
                                    'port' => $slave['port'],
                                    'ping' => $slave['ping'],
                                ]
                            );
                        }

                    } else {
                        $this->error('Failed to call parser', ['name' => $name, 'response'=>$json]);
                    }

                } catch (\Exception $e) {
                    $this->error('Failed to parse srv file', ['name' => $name, 'ex' => $e->getMessage()]);
                }
            }
        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            //echo $e->getMessage();
        }
    }
}
