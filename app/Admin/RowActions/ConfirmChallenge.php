<?php

namespace App\Admin\RowActions;

use App\Enums\ChallengeStatus;
use App\Models\Challenge;
use App\Notifications\ChallengeConfirmedNotification;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class ConfirmChallenge extends RowAction
{
    public function title()
    {
        return "&nbsp;<i class='feather icon-check-circle'></i>Confirm";
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();

        $order = Challenge::with(['plan', 'user'])->findOrFail($id);
        $order->update(['status'=>ChallengeStatus::CONFIRMED]);
        $user = $order->user;
        $plan = $order->plan;

        $user->notify(new ChallengeConfirmedNotification($order, $plan, $user->manager_id));

        return $this->response()->success("confirmed")->refresh();
    }

    public function parameters()
    {
        return [
        ];
    }
}
