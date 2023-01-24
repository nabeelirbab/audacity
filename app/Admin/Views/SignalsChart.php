<?php

namespace App\Admin\Views;

use App\Models\CopierSignal;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class SignalsChart extends Donut
{
    protected function init()
    {
        parent::init();

        // $color = Admin::color();
        // $colors = [$color->white(), $color->alpha('white2', 0.5)];

        $this->title(__('admin.followers'));
        $this->contentWidth(0, 12);
        $this->height(200);
        $this->chartHeight(160);

//        $this->chartColors($colors);
        $this->class('bg-info small-box');
    }

    public function render()
    {

        $signals = CopierSignal::whereManagerId(Admin::user()->id)->withCount('followers')->get();

        $labels = [];
        $counts = [];
        foreach($signals as $signal) {
            $labels[] = $signal->title;
            $counts[] = $signal->followers_count;
        }

        $this->chartLabels($labels);

        $this->chart([
            'series' => $counts
        ]);

        return parent::render();
    }

//     protected function withContent($desktop, $mobile)
//     {
//         $blue = Admin::color()->alpha('blue2', 0.5);

//         $style = 'margin-bottom: 8px';
//         $labelWidth = 120;

//         return $this->content(
//             <<<HTML
// <div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
//     <div style="width: {$labelWidth}px">
//         <i class="fa fa-circle text-primary"></i> {$this->labels[0]}
//     </div>
//     <div>{$desktop}</div>
// </div>
// <div class="d-flex pl-1 pr-1" style="{$style}">
//     <div style="width: {$labelWidth}px">
//         <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[1]}
//     </div>
//     <div>{$mobile}</div>
// </div>
// HTML
//         );
//     }
}