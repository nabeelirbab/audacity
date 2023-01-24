<?php

namespace App\Admin\Extensions\Tools;

use App\Models\Order;
use Dcat\Admin\Actions\Action;
use Dcat\Admin\Admin;
use Illuminate\Http\Request;

class ClearCopierErrorsButton extends Action
{

    public function __construct($title = null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        Order::where('ticket', '<', 0)
            ->whereHas('account', function ($q) {
                $q->whereManagerId(Admin::user()->id);
            })->delete();

        return $this->response()
            ->success(__('admin.errors_removed'));
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
