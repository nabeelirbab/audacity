<?php

namespace App\Models;

use App\Enums\CopierRiskType;
use App\Models\Account;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class CopierSubscription extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'copier_subscriptions';

    protected $casts = ['risk_type' => CopierRiskType::class];

    public function sources()
    {
        return $this->belongsToMany(Account::class, 'copier_subscription_source_accounts',
            'copier_subscription_id', 'account_id');
    }

    public function destination()
    {
        return $this->belongsToMany(Account::class, 'copier_subscription_dest_accounts',
            'copier_subscription_id', 'account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class,
            'copier_subscription_users',
            'copier_subscription_id',
            'user_id'
        );
    }
}
