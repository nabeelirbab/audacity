<?php

namespace App\Admin\RowActions;

use App\Enums\ChallengeStatus;
use App\Models\Challenge;
use App\Notifications\ChallengeCancelledNotification;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class CancelChallenge extends RowAction
{
    protected bool $byUser;
    public function __construct(bool $byUser = false, string $title = null) {
        $this->byUser = $byUser;

        parent::__construct($title);
    }

    public function title()
    {
        return "&nbsp;<i class='feather icon-x'></i>Cancel";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        $order = Challenge::with(['plan', 'user'])->findOrFail($id);
        $order->update(['status'=>ChallengeStatus::CANCELLED]);
        $user = $order->user;
        $plan = $order->plan;

        $byUser = $request->get('by_user');

        $user->notify(new ChallengeCancelledNotification($order, $plan, $user->manager_id, $byUser));

        return $this->response()->success("cancelled")->refresh();
    }

    public function parameters()
    {
        return [
            'by_user' => $this->byUser
        ];
    }
}
