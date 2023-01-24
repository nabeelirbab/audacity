<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class ChallengeAccountGeneratedMail extends ManagerTemplateMailable
{
    public int $order_id = 11;
    public string $user_name = 'Test User';
    public int $mt4_login = 123123;
    public string $mt4_password = 'pwd1234';
    public string $mt4_broker_server = 'Server-Demo';

    public function __construct( int $orderId, string $userName, int $accountNumber, string $accountPassword, string $brokerServer, int $managerId)
    {
        $this->order_id = $orderId;
        $this->user_name = $userName;
        $this->mt4_login = $accountNumber;
        $this->mt4_password = $accountPassword;
        $this->mt4_broker_server = $brokerServer;

        parent::__construct($managerId);
    }

}
