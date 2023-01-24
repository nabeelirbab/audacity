<?php

namespace App\Admin\RowActions;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class MarkAsRead extends RowAction
{
    public function title()
    {
        return "&nbsp;<i class='feather icon-eye'></i>&nbsp;";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        DatabaseNotification::find($id)->markAsRead();

        return $this->response()->success("read")->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
