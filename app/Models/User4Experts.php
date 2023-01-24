<?php
namespace App\Models;

use App\Models\User;
use App\Models\ExpertSubscription;
use App\Models\UserExpertSubscription;

class User4Experts extends User
{

    public function expertsubscriptions()
    {
        return $this->belongsToMany(
            ExpertSubscription::class,
            UserExpertSubscription::class,
            'user_id',
            'expert_subscription_id'
        );
    }

    public function experts()
    {
        return $this->hasManyDeepFromRelations($this->expertsubscriptions(),
            (new ExpertSubscription)->experts());
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            $roleModel = config('admin.database.roles_model');
            $role = $roleModel::whereSlug(config('user_experts'))->first();

            if($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

        });
    }

}
