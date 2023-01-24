<?php

namespace App\Models;

use App\Models\BrokerServer;

class BrokerServerWithAccounts extends BrokerServer
{

    protected $appends = [
        'countAccounts'
    ];

    public function getCountAccountsAttribute() {
        return $this->accounts()->count();
    }

}
