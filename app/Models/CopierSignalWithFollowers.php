<?php

namespace App\Models;

use App\Models\CopierSignal;

class CopierSignalWithFollowers extends CopierSignal
{
    protected $appends = ['countFollowers'];

    public function getCountFollowersAttribute() {
        return $this->followers()->count();
    }
}
