<?php

namespace App\Admin\Views;

use App\Models\Challenge;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;

class UserChallengesShortView extends LazyRenderable
{

    public function __construct($userId = null, array $payload = []) {

        $this->payload(['user_id' => $userId]);
        parent::__construct($payload);
    }

    public function render()
    {
        $data = [];

        $challenges = Challenge::with('plan:id,title,price')->whereUserId($this->user_id)->get();

        $data = $challenges->map(function(Challenge $challenge) {
            return [
                $challenge->id,
                $challenge->plan->title,
                '$'.$challenge->plan->price,
                $challenge->status->label(),
                $challenge->updated_at->diffForHumans()
            ];
        });

        return Table::make(['Id','Plan', 'Price', 'Status','Updated'], $data);
    }
}
