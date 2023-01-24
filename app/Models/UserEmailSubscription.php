<?php

namespace App\Models;

use App\Models\EmailSubscription;

use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class UserEmailSubscription extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'email_subscription_users';

    protected $fillable = ['manager_id'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription()
    {
        return $this->belongsTo(EmailSubscription::class, 'email_subscription_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($query) {
            $user = User::find($query->user_id);
            $roleModel = config('admin.database.roles_model');
            $roleId = $roleModel::whereSlug('user_email_subscriptions')->first()->id;

            $user->roles()->syncWithoutDetaching([$roleId]);
        });
    }
}
