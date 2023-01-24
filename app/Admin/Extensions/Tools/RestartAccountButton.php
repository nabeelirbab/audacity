<?php

namespace App\Admin\Extensions\Tools;

use App\Models\Account;
use App\Models\Order;
use Dcat\Admin\Actions\Action;
use Dcat\Admin\Admin;
use Illuminate\Http\Request;

class RestartAccountButton extends Action
{

    private $accountId;
    public function __construct($title = null, $accountId = null)
    {
        $this->accountId = $accountId;
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        $this->accountId = $request->get('account_id');

        $account = Account::find($this->accountId);
        if($account)
            $account->restart();

        return $this->response()
            ->success(__('admin.account_restarted'));
    }

    protected function parameters()
    {
        return ['account_id'=>$this->accountId];
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
