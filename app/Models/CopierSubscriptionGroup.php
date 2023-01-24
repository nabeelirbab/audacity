<?php

namespace App\Models;

use App\Models\CopierSubscription;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class CopierSubscriptionGroup extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_subscription_groups';

    protected $fillable = [ 'title', 'enabled' ];

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(
            CopierSubscription::class,
            'copier_subscription_group_pivot', 'copier_subscription_group_id', 'copier_subscription_id');
    }
}
