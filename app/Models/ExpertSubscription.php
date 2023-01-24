<?php

namespace App\Models;

use App\Models\Expert;

use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExpertSubscription extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'expert_subscriptions';

    protected $fillable = ['manager_id', 'name'];

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function experts() : BelongsToMany
    {
        return $this->belongsToMany(Expert::class, 'expert_subscription_experts');
    }

}
