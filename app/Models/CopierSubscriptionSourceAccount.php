<?php

namespace App\Models;

use App\Models\Account;
use App\Models\CopierSubscription;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class CopierSubscriptionSourceAccount extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_subscription_source_accounts';

    public function accounts()
    {
        return $this->belongsTo(Account::class);
    }

    public function subscription()
    {
        return $this->belongsTo(CopierSubscription::class);
    }
}
