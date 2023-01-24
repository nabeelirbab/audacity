<?php

namespace App\Admin\Extensions\Tools;

use App\Models\CopierSignalFollower;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class DisableCopierBatchActionFollower extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        foreach (CopierSignalFollower::find($this->getKey()) as $follower) {

            $follower->disable();
        }

        return $this->response()
            ->success(trans('admin.disabled_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
