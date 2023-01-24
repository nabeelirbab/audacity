<?php

namespace App\Models;

use App\Models\Account;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class EmailSubscriptionSourceAccount extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'email_subscription_source_accounts';

    public function accounts()
    {
        return $this->belongsTo(Account::class);
    }
}
