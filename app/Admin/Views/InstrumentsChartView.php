<?php

namespace App\Admin\Views;

use App\Models\Account;
use Dcat\Admin\Widgets\ApexCharts\Chart;

use Illuminate\Contracts\Support\Renderable;

class InstrumentsChartView implements Renderable
{

    private $accountNumber;
    private $from;
    private $to;

    public function __construct($accountNumber, $from = null, $to = null) {
        $this->accountNumber = $accountNumber;
        $this->from = $from;
        $this->to = $to;
    }

    public function render() {
        return $this->build();
    }

    private function build()
    {

        $data = Account::instrumentsChartData($this->accountNumber, $this->from, $this->to);

        $chart = new Chart();
            $chart->chart([
                'type'     => 'pie',
                'height' => 252,
                'toolbar' => [
                    'show' => false
                ],
            ])
            ->labels($data->keys()->toArray())
            ->series($data->values()->toArray());


        return $chart->render();
    }

}
