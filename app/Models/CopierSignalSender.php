<?php

namespace App\Models;

use App\Helpers\MT4Commands;
use App\Models\Account;
use App\Models\CopierSignal;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class CopierSignalSender extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_signal_senders';

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function signal() : BelongsTo
    {
        return $this->belongsTo(CopierSignal::class, 'signal_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function (CopierSignalSender $sender) {
            $account = Account::find($sender->account_id);

            MT4Commands::wsSendReload($account->account_number);

            Log::debug("sender::reload::updated", ['account'=>$account]);
        });

        static::created(function (CopierSignalSender $sender) {
            $account = Account::find($sender->account_id);

            MT4Commands::wsSendReload($account->account_number);
            Log::debug("sender::reload::created", ['account'=>$account]);
        });
    }

}
