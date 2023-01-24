<?php

namespace App\Admin\Views;

use App\Models\CopierSignalSubscription;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;

class UserSignalsShortView extends LazyRenderable
{

    public function __construct($userId = null, array $payload = []) {

        $this->payload(['user_id' => $userId]);
        parent::__construct($payload);
    }

    public function render()
    {
        $data = [];

        $signals = CopierSignalSubscription::with('signal_n_followers:id,title')->whereUserId($this->user_id)->get();

        $data = $signals->map(function(CopierSignalSubscription $sub) {
            return [
                $sub->id,
                $sub->signal_n_followers->title,
                $sub->signal_n_followers->countFollowers
            ];
        });

        return Table::make(['Id','Signal', 'followers'], $data);
    }
}
