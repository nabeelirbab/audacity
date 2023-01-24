<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class ChallengeConfirmedMail extends ManagerTemplateMailable
{
    public string $plan_title = 'Plan';
    public int $order_id = 11;

    public function __construct( string $planTitle, int $orderId, int $managerId)
    {
        $this->plan_title = $planTitle;
        $this->order_id = $orderId;

        parent::__construct($managerId);
    }

}
