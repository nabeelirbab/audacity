<?php

namespace App\Models;

use App\Enums\AccountStatus;

use App\Enums\JfxMode;
use App\Models\Account;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class EmailSubscription extends Model
{
    protected $table = 'email_subscriptions';

    protected $fillable = ['manager_id', 'name'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function sources()
    {
        return $this->belongsToMany(Account::class, 'email_subscription_source_accounts');
    }

    public function refreshWebHooks()
    {
        foreach ($this->sources()->get() as $item) {
            $item->jfx_mode = $item->jfx_mode|JfxMode::WEBHOOK_ENABLED;
            //todo::reload settings on vps
            //$item->account_status = AccountStatus::PENDING;
            $item->save();
        }
    }

    public function scopePublic() {
        return $this->where('is_public', 1);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($query) {
            //       $query->account_status = AccountStatus::PENDING;
        });
    }
}
