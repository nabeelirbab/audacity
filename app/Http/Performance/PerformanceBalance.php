<?php

namespace App\Http\Performance;

use App\Models\Performance;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Dcat\Admin\Widgets\Box;

class PerformanceBalance extends Box
{

    public function __construct(Performance $performance, $style) {

        parent::__construct( trans('trading-objectives.balance'), $this->buildEquityChart($performance) );
        $this->style($style);
    }

    private function buildEquityChart($performance)
    {

        $items = $performance->ordersAllClosed()->get(['pl']);

        $dataPL = array();
        $i = 0;
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->pl;
            $dataPL[] = number_format($sum, 2, '.', '');
        }

        //dd($dataPL);

        $chart = new Chart();
            $chart->chart([
                'type'     => 'area'
            ])
            ->dataLabels([
                'enabled' => false
            ])
            ->xaxis([
                'title' => [
                    'text' => trans('trading-objectives.nof_trades'),
                ],
                'type' => 'numeric'
            ])
            ->yaxis([
                'title' => [
                    'text' => trans('trading-objectives.balance'),
                ]
            ])
            ->series([
                [
                    'name' => trans('trading-objectives.equity'),
                    'data' => $dataPL
                ],
            ]);

        return $chart->render();
    }

}
