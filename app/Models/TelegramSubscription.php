<?php

namespace App\Models;

use App\Enums\JfxMode;
use App\Models\Account;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class TelegramSubscription extends Model
{

    use HasDateTimeFormatter;
    protected $table = 'telegram_subscriptions';

    protected $fillable = ['manager_id', 'title'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function sources()
    {
        return $this->belongsToMany(Account::class, 'telegram_subscription_source_accounts');
    }

    public function refreshWebHooks()
    {
        foreach ($this->sources()->get() as $item) {
            $item->jfx_mode = $item->jfx_mode|JfxMode::WEBHOOK_ENABLED;
            //todo::reload settings on vps
            //$item->account_status = AccountStatus::PENDING;
            //$item->save();
        }
    }
}
