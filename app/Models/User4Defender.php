<?php

namespace App\Models;

use App\Models\Campaign;
use App\Models\Licensing;
use App\Models\User;

class User4Defender extends User
{

    public function licensing()
    {
        return $this->hasOne(Licensing::class,'user_id');
    }

    public function campaign()
    {
        return $this->hasOneThrough(Campaign::class, Licensing::class,
            'user_id', 'id', 'user_id', 'campaign_id');
    }

}
