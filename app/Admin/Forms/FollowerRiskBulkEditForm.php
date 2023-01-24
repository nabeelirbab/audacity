<?php

namespace App\Admin\Forms;

use App\Models\CopierSignal;
use App\Models\CopierSignalFollower;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Facades\Log;

class FollowerRiskBulkEditForm extends Form implements LazyRenderable
{
    use LazyWidget;

    public function __construct($signalId = null, array $payload = []) {

        $this->payload(['signal_id' => $signalId]);
        parent::__construct($payload);
    }

    public function handle(array $input)
    {
        $ids = explode(',', $input['follower-ids']);
        $followers = CopierSignalFollower::findMany($ids);

        foreach($followers as $follower) {
            $follower->update($input);
        }

        return $this->response()->success(__('admin.update_succeeded'))->refresh();
    }

    public function form()
    {
        $this->display('title','signal');

        $this->hidden('follower-ids');
        $this->number('max_open_positions');
        $this->number('max_lots_per_trade');
        $this->decimal('min_balance');
    }

    public function default()
    {

        $item = CopierSignal::find($this->payload['signal_id'],
            [
                'title', 'max_open_positions', 'max_lots_per_trade', 'min_balance'
            ]
        );
        return $item->toArray();
    }
}
