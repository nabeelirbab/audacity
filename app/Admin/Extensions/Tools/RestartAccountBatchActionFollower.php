<?php

namespace App\Admin\Extensions\Tools;

use App\Models\Account;
use App\Models\CopierSignalFollower;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class RestartAccountBatchActionFollower extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        foreach (CopierSignalFollower::find($this->getKey()) as $follower) {

            $account = Account::find($follower->account_id);

            $account->restart();
        }

        return $this->response()
            ->success(trans('admin.restarted_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
