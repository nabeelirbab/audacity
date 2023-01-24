<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class UserActivationRequiredMail extends ManagerTemplateMailable
{
    public $token;
    public $link;

    public function __construct( string $link, int $managerId)
    {
        $this->link = $link;

        parent::__construct($managerId);
    }
}