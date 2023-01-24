<?php

namespace App\Admin\Extensions\Tools;

use App\Models\SystemLog;
use Dcat\Admin\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClearSystemLogButton extends Action
{

    public function __construct($title = null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        SystemLog::truncate();
        return $this->response()
            ->success(__('admin.truncated_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

    protected function html()
    {
        $this->appendHtmlAttribute('class', ' btn btn-primary btn-outline btn-outline');
        $this->defaultHtmlAttribute('href', 'javascript:void(0)');

        return <<<HTML
        <div class="btn-group pull-right" style="margin-right: 10px">
    <a {$this->formatHtmlAttributes()}>
        <i class="fa fa-recycle"></i>&nbsp;&nbsp;{$this->title()}
    </a>
</div>
HTML;
    }
}
