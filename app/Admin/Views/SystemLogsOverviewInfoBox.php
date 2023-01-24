<?php

namespace App\Admin\Views;

use App\Models\SystemLog;
use Dcat\Admin\Widgets\InfoBox;
use Monolog\Logger;

class SystemLogsOverviewInfoBox extends InfoBox
{
    public function render()
    {

        $critical = SystemLog::where('level', Logger::CRITICAL)->count();
        $emergency = SystemLog::where('level', Logger::EMERGENCY)->count();
        $errors = SystemLog::where('level', Logger::ERROR)->count();
        $warning = SystemLog::where('level', Logger::WARNING)->count();

        $content = $this->formatLine('system-log.critical', $critical);
        $content .= $this->formatLine('system-log.emergency', $emergency);
        $content .= $this->formatLine('system-log.errors', $errors);
        $content .= $this->formatLine('system-log.warning', $warning);

        $this->content($content);

        return parent::render();
    }

    private function formatLine($title, $val) {

        $class = 'white';

        if($val>0)
            $class = 'danger';

        $title = trans($title);

        return
        <<<HTML
        <div class="d-flex pl-1 pr-1" style="margin-bottom: 8px">
        <div style="width: 120px">
            <i class="fa fa-circle text-{$class}"></i> $title
        </div>
        <div>{$val}</div>
    </div>
    HTML;
    }

}