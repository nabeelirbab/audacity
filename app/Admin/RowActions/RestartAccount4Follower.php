<?php

namespace App\Admin\RowActions;

use App\Models\Account;
use App\Models\CopierSignalFollower;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class RestartAccount4Follower extends RowAction
{
    public function title()
    {
        return "&nbsp;<i class='feather icon-refresh-cw'></i>&nbsp;";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        $follower = CopierSignalFollower::find($id);

        if($follower) {
            $account = $follower->account()->first();

            if($account)
                $account->restart();
        }


        return $this->response()->success(__('admin.restarted_success'))->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
