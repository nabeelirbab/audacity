<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class AccountInvalidMail extends ManagerTemplateMailable
{
    public int $account_number = 123123;
    public string $broker_server = 'Server-Demo';

    public function __construct(int $accountNumber, string $brokerServer, int $managerId = null)
    {
        $this->account_number = $accountNumber;
        $this->broker_server = $brokerServer;

        parent::__construct($managerId);
    }

}
