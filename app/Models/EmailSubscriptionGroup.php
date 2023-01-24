<?php

namespace App\Models;

use App\Models\EmailSubscription;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class EmailSubscriptionGroup extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'email_subscription_groups';

    protected $fillable = [ 'title', 'enabled' ];

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(
            EmailSubscription::class,
            'email_subscription_group_pivot', 'email_subscription_group_id', 'email_subscription_id');
    }
}
