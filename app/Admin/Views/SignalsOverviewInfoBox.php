<?php

namespace App\Admin\Views;

use App\Models\CopierSignal;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\InfoBox;

class SignalsOverviewInfoBox extends InfoBox
{
    public function render()
    {

        $signals = CopierSignal::whereManagerId(Admin::user()->id)->withCount('followers')->get();

        $content = '';

        foreach($signals as $signal) {
            $content .= $this->formatLine($signal->title, $signal->followers_count, 'circle');
        }

        $this->content($content);

        return parent::render();
    }

    private function formatLine($title, $val, $icon) {
        return
        <<<HTML
        <div class="d-flex pl-1 pr-1" style="margin-bottom: 8px">
            <div style="width: 160px">
                <i class="fa fa-$icon text-primary"></i> {$title}
            </div>
            <div>{$val}</div>
        </div>
HTML;
    }
}