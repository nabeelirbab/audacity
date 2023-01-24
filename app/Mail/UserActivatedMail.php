<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class UserActivatedMail extends ManagerTemplateMailable
{
    public string $login = 'dummy-login';
    public string $url = 'http://example.com';

    public function __construct( string $login, string $loginUrl, int $managerId)
    {
        $this->login = $login;
        $this->url = $loginUrl;

        parent::__construct($managerId);
    }
}