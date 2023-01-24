<?php

namespace App\Http\Controllers\Performance;

use App\Http\Performance\PerformanceAdvStat;
use App\Http\Performance\PerformanceBalance;
use App\Http\Performance\PerformanceInfo;
use App\Http\Performance\PerformanceInstruments;
use App\Http\Performance\PerformanceObjectives;
use App\Http\Performance\PerformanceOrdersView;
use App\Models\PerformanceWithObjectives;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Illuminate\Routing\Controller;

class PerformanceMetricsController extends Controller
{

    public function external(string $slug, Content $content) {
        $content->view('trading-objectives.metrics');

        return $this->metrics($slug, $content);
    }

    public function internal(string $slug, Content $content) {
        return $this->metrics($slug, $content);
    }

    private function metrics(string $slug, Content $content) {

        $performance = PerformanceWithObjectives::whereSlug($slug)->with(['target', 'stat', 'account_stat'])->first();

        $content->title(trans('trading-objectives.metrics'));

        if(!$performance) {
            $content->description(trans('trading-objectives.not_found'));

            return $content;
        }

        $content->title(trans('trading-objectives.metrics'));
        $content->body(function(Row $row) use($performance) {

            $equity = new PerformanceBalance($performance, 'success');
            $row->column(8, $equity->render());

            $info = new PerformanceInfo($performance->slug, $performance);
            $row->column(4, $info->render());
        });

        $content->body(function(Row $row) use($performance) {
            $obj = new PerformanceObjectives($performance->target, $performance->stat, $performance->objectives);
            $row->column(8, $obj->render());
        });


        $content->body(function(Row $row) use($performance) {
            $advStat = new PerformanceAdvStat($performance->account_stat);
            $row->column(8, $advStat->render());

            $instr = new PerformanceInstruments($performance, 'success');
            $row->column(4, $instr->render());
        });

        $content->body(function(Row $row) use($performance) {
            $orders = new PerformanceOrdersView($performance);
            $row->column(12, $orders->render());
        });

        return $content;
    }

}
