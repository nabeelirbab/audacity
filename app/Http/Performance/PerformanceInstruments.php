<?php

namespace App\Http\Performance;

use App\Models\Performance;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Dcat\Admin\Widgets\Box;

class PerformanceInstruments extends Box
{

    public function __construct(Performance $performance, $style) {

        parent::__construct( trans('trading-objectives.instruments'), $this->buildChart($performance) );
        $this->style($style);
    }

    private function buildChart($performance)
    {

        $data = $performance->instrumentsDistribution();

        $chart = new Chart();
        $chart->chart([
            'type'     => 'pie'
        ])
        ->labels(array_keys($data))
        ->series(array_values($data));

        return $chart->render();
    }

}
