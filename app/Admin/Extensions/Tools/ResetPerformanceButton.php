<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\PerformanceStatus;
use App\Models\Performance;
use App\Models\PerformanceTarget;
use Dcat\Admin\Actions\Action;
use Illuminate\Http\Request;

class ResetPerformanceButton extends Action
{

    private $perfId;
    public function __construct($title = null, $perfId = null)
    {
        $this->perfId = $perfId;
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        $this->perfId = $request->get('perf_id');

        PerformanceTarget
            ::find($this->perfId)
            ->reset();

        $perf = Performance::find($this->perfId);
        $perf->status = PerformanceStatus::CALCULATING;
        $perf->save();

        return $this->response()
            ->success(__('admin.reset'));
    }

    protected function parameters()
    {
        return ['perf_id'=>$this->perfId];
    }

    protected function html()
    {
        $this->appendHtmlAttribute('class', ' btn btn-sm btn-primary');
        $this->defaultHtmlAttribute('href', 'javascript:void(0)');

        return <<<HTML
        <div class="btn-group pull-right btn-mini" style="margin-right: 5px">
    <a {$this->formatHtmlAttributes()}>
        <i class="feather icon-refresh-cw"></i>&nbsp;&nbsp;{$this->title()}
    </a>
</div>
HTML;
    }
}
