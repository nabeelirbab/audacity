<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class AccountGeneratedMail extends ManagerTemplateMailable
{
    public string $user_name = 'Test User';
    public int $mt4_login = 123123;
    public string $mt4_password = 'pwd1234';
    public string $mt4_broker_server = 'Server-Demo';

    public function __construct( string $userName, int $accountNumber, string $accoutPassword, string $brokerServer, int $managerId)
    {
        $this->user_name = $userName;
        $this->mt4_login = $accountNumber;
        $this->mt4_password = $accoutPassword;
        $this->mt4_broker_server = $brokerServer;

        parent::__construct($managerId);
    }

}
