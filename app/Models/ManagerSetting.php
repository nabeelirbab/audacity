<?php

namespace App\Models;

use App\Enums\CopierType;
use App\Models\Account;
use App\Models\CopierSubscription;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManagerSetting extends Model
{

    use HasDateTimeFormatter;
    protected $table = 'manager_settings';

    protected $fillable = [
        'user_id', 'max_copiers', 'max_senders', 'max_followers', 'can_edit_brokers'
    ];

    protected $appends = [
        'senderCount',
        'followerCount',
        'copierCount'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users() : HasMany
    {
        return $this->hasMany(User::class, 'manager_id', 'user_id');
    }

    public function copiers() : HasMany
    {
        return $this->hasMany(CopierSubscription::class, 'manager_id', 'user_id');
    }

    public function accounts() : HasMany
    {
        return $this->hasMany(Account::class, 'manager_id', 'user_id');
    }

    public function senders() : HasMany
    {
        return $this->accounts()->where('copier_type', CopierType::SENDER);
    }

    public function followers() : HasMany
    {
        return $this->accounts()->where('copier_type', CopierType::FOLLOWER);
    }

    public function getSenderCountAttribute() : int
    {
        return $this->senders()->count();
    }

    public function getFollowerCountAttribute() : int
    {
        return $this->followers()->count();
    }

    public function getCopierCountAttribute() : int
    {
        return $this->copiers()->count();
    }

    public function canHaveSenders() : bool
    {

        if($this->max_senders == 0)
            return true;

        return $this->senderCount < $this->max_senders;
    }

    public function canHaveFollowers() : bool
    {

        if($this->max_followers == 0)
            return true;

        return $this->followerCount < $this->max_followers;
    }

    public function canHaveAccounts() : bool
    {
        return $this->canHaveFollowers() || $this->canHaveSenders();
    }

    public function canHaveCopiers() : bool
    {

        if($this->max_copiers == 0)
            return true;

        return $this->copierCount < $this->max_copiers;
    }

    public static function getCurrent() : ?ManagerSetting {
        return self::whereUserId(Admin::id())->first();
    }
}
