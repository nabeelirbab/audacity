<?php

namespace App\Admin\Extensions\Tools;

use App\Models\Account;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class RestartAccountBatchAction extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        foreach (Account::find($this->getKey()) as $account) {
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
