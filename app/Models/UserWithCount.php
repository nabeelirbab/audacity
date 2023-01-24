<?php
namespace App\Models;

use App\Models\User;

class UserWithCount extends User
{
    protected $appends = [
        'countAccounts',
        'countSignals',
        'countChallenges',
    ];

    public function getCountAccountsAttribute()
    {
        return $this->accounts()->count();
    }

    public function getCountSignalsAttribute()
    {
        return $this->signals()->count();
    }

    public function getCountChallengesAttribute()
    {
        return $this->challenges()->count();
    }
}
