<?php

namespace App\Admin\RowActions;

use App\Models\UserBrokerServer;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class MarkBrokerServerAsDefault extends RowAction
{
    public function title()
    {
        return "<i class='feather icon-check'></i>".__('admin.set_default');
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        UserBrokerServer::find($id)->MarkAsDefault();

        return $this->response()->success(__('admin.update_succeeded'))->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
