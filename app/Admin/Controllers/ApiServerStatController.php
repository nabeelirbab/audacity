<?php

namespace App\Admin\Controllers;

use App\Enums\ApiServerStatus;
use App\Helpers\ApiServerHelper;
use App\Http\Controllers\Controller;
use App\Models\ApiServer;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Dcat\Admin\Widgets\Box;

class ApiServerStatController extends Controller
{

    protected $translation = 'api-server-stat';

    private function prepareRow(Row $row, ApiServer $server, int $size) {
        $chart = $this->buildServerChart($server->host_name);

        $box = new Box('<b>'.$server->title.'</b> ('.$server->api_server_status->label().')' .' Accounts: '. $server->accounts_count,
            "<div id=\"server{$server->host_name}\"></div>$chart"
        );

        if ($server->api_server_status == ApiServerStatus::UP) {
            $box = $box->style('info');
        }

        if ($server->api_server_status == ApiServerStatus::DOWN) {
            $box = $box->style('danger');
        }


        $row->column($size, $box->solid() );
    }

    public function index(Content $content)
    {

        $content->translation($this->translation);
        $content->title( admin_trans_label('title') );

        if (Admin::user()->isAdministrator()) {
            $servers = ApiServer::enabled()->withCount('accounts')->get();
        } else
            $servers = ApiServer::withCount('accounts')->whereManagerId(Admin::user()->id)->get();

        $n = count($servers);
        $itemsPerRow = 2;
        for( $i = 0; $i < $n; $i++) {

            $row = new Row;

            for($j = 0; $j < $itemsPerRow; $j++) {
                $this->prepareRow($row, $servers[$i], 12/$itemsPerRow);
                $i++;
                if($i >= $n) {
                    break;
                }
            }
            $i--;

            $content->row($row);
        }

        return $content;
    }

    private function buildServerChart($hostName)
    {
        $itemsCpu = ApiServerHelper::getStatCpuFull($hostName);
        $dataCPU = array();
        $dataRAM = array();

        foreach ($itemsCpu as $item) {
            $dataCPU[] =  array(strtotime($item->GetDate()) * 1000, $item->GetVal());
        }

        $itemsMem = ApiServerHelper::getStatMemFull($hostName);
        foreach ($itemsMem as $item) {
            $dataRAM[] =  array(strtotime($item->GetDate()) * 1000, $item->GetVal());
        }

        $chart = new Chart();
            $chart->chart([
                'type'     => 'area',
                'toolbar' => [
                    'show' => false
                ],
            ])
            ->tooltip ([
                'x' => [
                    'format' => 'HH:mm:ss'
                ],
            ])
            ->dataLabels([
                'enabled' => false
            ])
            ->xaxis([
                'type' => 'datetime'
            ])
            ->yaxis([
                'title' => [
                    'text' => '%',
                ]
            ])
            ->series([
                [
                    'name' => 'CPU',
                    'data' => $dataCPU
                ],
                [
                    'name' => 'RAM',
                    'data' => $dataRAM
                ]
            ]);

        return $chart->render();
    }
}
