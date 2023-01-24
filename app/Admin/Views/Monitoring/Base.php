<?php

namespace App\Admin\Views\Monitoring;

use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;
use Spatie\ServerMonitor\Models\Check;
use Spatie\ServerMonitor\Models\Record;

abstract class Base extends Line
{

    abstract public function type() : string;

    protected function init()
    {
        parent::init();

        $this->dropdown([
            '1' => 'Today',
            '7' => 'Last Week',
            '30' => 'Last Month'
        ]);
    }

    public function handle(Request $request)
    {
        $lastValue = Check::whereType($this->type())->first();

        $this->withContent($lastValue->last_run_value, $lastValue->last_ran_at->format('h:i'));

        switch ($request->get('option')) {
            default:
            case '1':
                $records = Record::whereType($this->type())->today()->pluck('value');
                break;
            case '7':
                $records = Record::whereType($this->type())->lastWeek()->pluck('value');
                break;
            case '30':
                $records = Record::whereType($this->type())->lastMonth()->pluck('value');
                break;
        }

        $this->withChart($records->toArray());
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => [
                [
                    'name' => $this->type(),
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function withContent($value, $date)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$value}%</h2>
    <span class="mb-0 mr-1 text-80">Time: {$date}</span>
</div>
HTML
        );
    }
}