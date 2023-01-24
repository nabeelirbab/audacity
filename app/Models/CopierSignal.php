<?php

namespace App\Models;

use App\Helpers\MT4Commands;
use App\Models\Account;
use App\Models\CopierSignalEmailSetting;
use App\Models\CopierSignalFollower;
use App\Models\CopierSignalPageSetting;
use App\Models\CopierSignalSender;
use App\Models\CopierSignalSubscription;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramChannel;

/**
 * @OA\Schema(
 *     title="Signal",
 *     description="Signal model",
 *     @OA\Xml(
 *         name="Signal"
 *     ),
 * @OA\Property(
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     description="Title",
 *     example="Signal1"
 * ),
 * @OA\Property(
 *     property="risk_type",
 *     title="Risk Type",
 *     description="Risk Type",
 *     format="string",
 *     enum={"MULTIPLIER", "FIXED_LOT", "MONEY_RATIO", "RISK_PERCENT", "SCALING"},
 *     example="MULTIPLIER"
 * )
 * )
*/
class CopierSignal extends Model
{
    use HasDateTimeFormatter;
    use Notifiable;

    protected $table = 'copier_signals';

    protected $fillable = ['title', 'risk_type', 'manager_id', 'creator_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->slug = Str::random(6);
        });

        static::updated(function (CopierSignal $signal) {
            $accounts = $signal->senders()->get(['account_number']);

            foreach($accounts as $account) {
                MT4Commands::wsSendReload($account->account_number);

                Log::debug("sender::reload::updated", ['account'=>$account]);
            }
        });

        static::created(function (CopierSignal $signal) {
            $accounts = $signal->senders()->get(['account_number']);

            foreach($accounts as $account) {
                MT4Commands::wsSendReload($account->account_number);

                Log::debug("sender::reload::created", ['account'=>$account]);
            }
        });
    }

    public function scopePublic($query) {
        return $query->where('is_public', 1);
    }

    public function senders() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, CopierSignalSender::class,
            'signal_id', 'account_id');
    }

    public function followers() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, CopierSignalFollower::class,
            'signal_id', 'account_id');
    }

    public function followers_settings() : HasMany
    {
        return $this->hasMany(CopierSignalFollower::class, 'signal_id');
    }

    public function email_settings() : HasOne
    {
        return $this->hasOne(CopierSignalEmailSetting::class, 'signal_id');
    }

    public function page_settings() : HasOne
    {
        return $this->hasOne(CopierSignalPageSetting::class, 'signal_id');
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subscribers() : BelongsToMany
    {
        return $this->belongsToMany(User::class,
            CopierSignalSubscription::class,
            'signal_id',
            'user_id'
        );
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationForTelegram(Notification $notification = null) {
        return $this->telegram_chat_id;
    }
}
