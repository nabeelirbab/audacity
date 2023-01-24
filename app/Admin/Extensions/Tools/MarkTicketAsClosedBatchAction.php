<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\HelpdeskTicketStatus;
use App\Models\HelpdeskTicket;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class MarkTicketAsClosedBatchAction extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {
        HelpdeskTicket::whereIn('id',$this->getKey())->update(['status' => HelpdeskTicketStatus::CLOSED]);

        return $this->response()
            ->success(trans('admin.closed_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
