<?php

namespace App\Admin\Views;

use App\Models\Account;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class GrowthView extends Line
{

    public function __construct(int $accountNumber = 0, $from = null, $to = null, $height = 200) {
        $this->parameters['account_number'] = $accountNumber;
        $this->parameters['from'] = $from;
        $this->parameters['to'] = $to;

        $this->chartHeight($height);
        parent::__construct();
    }

    protected $chartOptions = [
        'chart' => [
            'type' => 'area',
            'toolbar' => [
                'show' => false,
            ],
            'sparkline' => [
                'enabled' => false,
            ],
        ],
        'tooltip' => [
            'x' => [
                'format' => 'dd MMM yyyy'
            ],
        ],
        'xaxis' => [
            'type' => 'datetime',
            'labels' => [
                'show' => false,
            ],
            'axisBorder' => [
                'show' => false,
            ],
        ],
        'yaxis' => [
            'y' => 0,
            'offsetX' => 0,
            'offsetY' => 0,
            'padding' => ['left' => 0, 'right' => 0],
        ],
        'dataLabels' => [
            'enabled' => false,
        ],
        'stroke' => [
            'width' => 2.5,
            'curve' => 'smooth',
        ],
        'fill' => [
            'opacity' => 0.1,
            'type' => 'solid',
        ],
    ];

    protected function init()
    {
        parent::init();

        $this->dropdown([
            'balance' => __('mt4-account.balance'),
            'profit' => __('mt4-account.profit'),
            'growth' => __('mt4-account.growth')
        ]);
    }

    public function handle(Request $request)
    {
        $option = $request->get('option');
        $accountNumber = $request->get('account_number');
        $from = $request->get('from');
        $to = $request->get('to');

        switch ($option) {
            case 'growth':
                $data = Account::growthChartData($accountNumber, $from, $to);
                $this->chart([
                    'series' => [
                        [
                            'name' => __('mt4-account.growth').' %',
                            'data' => $data,
                        ],
                    ],
                ]);
                break;
            case 'balance':
                $data = Account::balanceChartData($accountNumber, $from, $to);
                $this->chart([
                    'series' => [
                        [
                            'name' => __('mt4-account.balance').' USD',
                            'data' => $data,
                        ],
                    ],
                ]);
                break;
            default:
            case 'profit':
                $data = Account::profitChartData($accountNumber, $from, $to);
                $this->chart([
                    'series' => [
                        [
                            'name' => __('mt4-account.profit').' USD',
                            'data' => $data,
                        ],
                    ],
                ]);
                break;
        }
    }

}