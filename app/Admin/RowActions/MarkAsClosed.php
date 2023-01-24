<?php

namespace App\Admin\RowActions;

use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicket;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class MarkAsClosed extends RowAction
{
    public function title()
    {
        return "<i class='feather icon-check-circle'></i>&nbsp;Close";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        HelpdeskTicket::findOrFail($id)->update(['status'=>HelpdeskTicketStatus::CLOSED]);

        return $this->response()->success("closed")->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
