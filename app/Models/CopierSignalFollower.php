<?php

namespace App\Models;

use App\Enums\CopierRiskType;
use App\Helpers\MT4Commands;

use App\Models\Account;
use App\Models\AccountStat;
use App\Models\CopierSignal;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class CopierSignalFollower extends Model
{
    use HasDateTimeFormatter;
    use Notifiable;

    protected $table = 'copier_signal_followers';
    protected $hidden = ['pivot'];

    protected $casts = ['risk_type' => CopierRiskType::class ];

    protected $fillable = [
        'risk_type', 'scaling_factor', 'fixed_lot', 'lots_multiplier', 'max_lots_per_trade', 'money_ratio_lots',
        'money_ratio_dol', 'price_diff_accepted_pips', 'min_balance', 'live_time', 'copier_enabled',
        'email_enabled', 'copy_existing', 'max_open_positions', 'max_risk',
        'dont_copy_sl_tp', 'sender_sl_offset_pips', 'sender_tp_offset_pips'
    ];


    public function fillSignalDefaults(array $overrides = []) {
        $defs = $this->signal()->get([
            'risk_type', 'scaling_factor', 'fixed_lot', 'lots_multiplier', 'max_lots_per_trade', 'money_ratio_lots',
            'money_ratio_dol', 'price_diff_accepted_pips', 'min_balance', 'live_time',
            'copier_enabled', 'email_enabled', 'copy_existing', 'max_open_positions',
            'dont_copy_sl_tp', 'sender_sl_offset_pips', 'sender_tp_offset_pips'
            ])->first()->toArray();

        $defs = array_merge($defs, $overrides);

        $this->update( $defs );
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function signal() : BelongsTo
    {
        return $this->belongsTo(CopierSignal::class, 'signal_id');
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user() : HasOneThrough
    {
        return $this->hasOneThrough(User::class, Account::class, 'id', 'id', 'account_id', 'user_id');
    }

    public function stat() : HasOneThrough
    {
        return $this->hasOneThrough(AccountStat::class, Account::class, 'id', 'account_number', 'account_id', 'account_number');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function(CopierSignalFollower $follower) {
            if( $follower->copier_enabled == 1 ) {
                $follower->copier_enabled_at = now();
            }
            // if($query->copy_existing == 1) {
            //     $query->live_time = 0;
            // } else {
            //     $query->live_time = 300;
            // }
        });

        static::updating(function(CopierSignalFollower $follower) {
            if( $follower->copier_enabled != $follower->getOriginal('copier_enabled') && $follower->copier_enabled == 1 ) {
                $follower->copier_enabled_at = now();
            }
            // if($query->copy_existing == 1) {
            //     $query->live_time = 0;
            // } else {
            //     $query->live_time = 300;
            // }
        });

        static::updated(function (CopierSignalFollower $follower) {
            $account = Account::find($follower->account_id);

            if($account)
                MT4Commands::wsSendReload($account->account_number);
        });

        static::created(function (CopierSignalFollower $follower) {

            $account = Account::find($follower->account_id);
            if($account)
                MT4Commands::wsSendReload($account->account_number);
            //Log::info("follower::reload::created", ['account'=>$account]);
        });

        static::deleted(function (CopierSignalFollower $follower) {

            $account = Account::find($follower->account_id);
            if($account)
                MT4Commands::wsSendReload($account->account_number);
            //Log::info("follower::reload::created", ['account'=>$account]);
        });
    }

    public function disable() {
        $this->copier_enabled = 0;
        $this->save();
    }

    public static function formatRiskString($data) : string
    {
        $risk = $data->risk_type->label();
        $opt = match($data->risk_type) {
            CopierRiskType::MULTIPLIER => $data->lots_multiplier,
            CopierRiskType::FIXED_LOT => $data->fixed_lot,
            CopierRiskType::RISK_PERCENT => $data->max_risk,
            CopierRiskType::SCALING => $data->scaling_factors,
            CopierRiskType::MONEY_RATIO => $data->money_ratio_dol . ':' . $data->money_ratio_lot,
            default => $data->lots_multiplier
        };

        return $risk . ' (' . $opt . ')';
    }

    public function getEmailAttribute() : string {
        return $this->email ?? $this->user()->first(['email'])->email;
    }

    public function routeNotificationForManagerMailer(Notification $notification = null) {
        return $this->signals_email ?? $this->user()->value('email');
    }

    public static function hasPastDue() : bool {
        return self::whereHas('account', function($query) {
            return $query->whereUserId(Admin::id());
        })->where('is_past_due', 1)->exists();
    }
}
