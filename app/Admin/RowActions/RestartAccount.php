<?php

namespace App\Admin\RowActions;

use App\Models\Account;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class RestartAccount extends RowAction
{
    public function title()
    {
        return "&nbsp;<i class='feather icon-refresh-cw'></i>&nbsp;";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        $account = Account::find($id);

        if($account)
            $account->restart();

        return $this->response()->success(trans('admin.restarted_success'))->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
