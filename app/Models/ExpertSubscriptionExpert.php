<?php

namespace App\Models;

use App\Models\Expert;
use App\Models\ExpertSubscription;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ExpertSubscriptionExpert extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'expert_subscription_experts';

    public function expert()
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }

    public function subscription()
    {
        return $this->belongsTo(ExpertSubscription::class, 'expert_subscription_id');
    }
}
