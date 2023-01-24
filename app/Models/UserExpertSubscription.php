<?php

namespace App\Models;

use App\Models\ExpertSubscription;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExpertSubscription extends Model
{
    protected $table = 'expert_subscription_users';

    protected $fillable = ['manager_id'];

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription() : BelongsTo
    {
        return $this->belongsTo(ExpertSubscription::class, 'expert_subscription_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($query) {
            $user = User::find($query->user_id);
            $roleModel = config('admin.database.roles_model');
            $roleId = $roleModel::whereSlug('user_expert_subscriptions')->first()->id;

            $user->roles()->syncWithoutDetaching([$roleId]);
        });
    }
}
