<?php

namespace App\Admin\RowActions;

use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicket;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class MarkAsOpen extends RowAction
{
    public function title()
    {
        return "<i class='feather icon-corner-up-left'></i>&nbsp;Re-open";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        HelpdeskTicket::findOrFail($id)->update(['status'=>HelpdeskTicketStatus::OPEN]);

        return $this->response()->success("opened")->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
