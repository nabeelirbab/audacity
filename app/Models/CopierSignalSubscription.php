<?php

namespace App\Models;

use App\Models\CopierSignal;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CopierSignalSubscription extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_signal_subscriptions';

    protected $fillable = ['user_id', 'signal_id', 'manager_id'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function signal() : BelongsTo
    {
        return $this->belongsTo(CopierSignal::class, 'signal_id');
    }

    public function signal_n_followers() : BelongsTo
    {
        return $this->belongsTo(CopierSignalWithFollowers::class, 'signal_id');
    }

}
