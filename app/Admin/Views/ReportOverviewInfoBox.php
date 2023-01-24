<?php

namespace App\Admin\Views;

use App\Models\AccountStat;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\InfoBox;

class ReportOverviewInfoBox extends InfoBox
{

    public function render()
    {

        $maxBalance = AccountStat::with('account')->whereHas('account', function($query) {
            $query->whereManagerId(Admin::user()->id);
        })->max('balance');
        $minBalance = AccountStat::with('account')->whereHas('account', function($query) {
            $query->whereManagerId(Admin::user()->id);
        })->where('balance', '>', '0')->min('balance');

        $avgBalance = AccountStat::with('account')->whereHas('account', function($query) {
            $query->whereManagerId(Admin::user()->id);
        })->avg('balance');

        $totalBalance = AccountStat::with('account')->whereHas('account', function($query) {
            $query->whereManagerId(Admin::user()->id);
        })->sum('balance');

        $content = $this->formatLine('highest', number_format($maxBalance, 2), 'circle');
        $content .= $this->formatLine('lowest', number_format($minBalance,2), 'circle');
        $content .= $this->formatLine('average', number_format($avgBalance, 2 ), 'circle');
        $content .= $this->formatLine('total', number_format($totalBalance, 2 ), 'circle');

        $this->content($content);

        return parent::render();
    }

    private function formatLine($title, $val, $icon) {
        $title = __('admin.'.$title);

        if($val < 0) {
            $val = '-$'.$val;
        } else {
            $val = '$'.$val;
        }

        return
        <<<HTML
        <div class="d-flex pl-1 pr-1" style="margin-bottom: 8px">
            <div style="width: 180px">
                <i class="fa fa-$icon text-primary"></i> {$title}
            </div>
            <div>{$val}</div>
        </div>
HTML;
    }
}