<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\AccountStatus;
use App\Jobs\ProcessPendingAccount;
use App\Models\Account;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class MoveAccountBatchAction extends BatchAction
{
    protected $ip;

    public function __construct($ip = null, $title = null)
    {
        $this->ip = $ip;
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        $ip = $request->get('ip');

        foreach (Account::find($this->getKey()) as $account) {

            $account->move($ip);
        }

        return $this->response()
            ->success(trans('admin.move_succeeded'))
            ->redirect($request->get('url'));
    }

    protected function parameters()
    {
        return ['ip'=>$this->ip, 'url' => request()->fullUrl()];
    }
}
