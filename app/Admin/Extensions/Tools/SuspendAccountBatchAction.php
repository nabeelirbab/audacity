<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\AccountStatus;
use App\Models\Account;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class SuspendAccountBatchAction extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        foreach (Account::find($this->getKey()) as $account) {
            $account->account_status = AccountStatus::SUSPEND;
            $account->save();
        }

        return $this->response()
            ->success(trans('admin.suspend_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
