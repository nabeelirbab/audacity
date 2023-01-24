<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Actions\Action;
use App\Models\AccountStat;
use Illuminate\Http\Request;

class ResetAccountStatButton extends Action
{

    private $accountNumber;
    public function __construct($title = null, $accountNumber = null)
    {
        $this->accountNumber = $accountNumber;
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        $this->accountNumber = $request->get('account_number');

        $accountStat = AccountStat::find($this->accountNumber);
        if($accountStat)
            $accountStat->reset();

        return $this->response()
            ->success(__('admin.reset_success'));
    }

    protected function parameters()
    {
        return ['account_number'=>$this->accountNumber];
    }

    protected function html()
    {
        $this->appendHtmlAttribute('class', ' btn btn-sm btn-primary');
        $this->defaultHtmlAttribute('href', 'javascript:void(0)');

        return <<<HTML
        <div class="btn-group pull-right btn-mini" style="margin-right: 5px">
    <a {$this->formatHtmlAttributes()}>
        <i class="feather icon-octagon"></i>&nbsp;&nbsp;{$this->title()}
    </a>
</div>
HTML;
    }
}
