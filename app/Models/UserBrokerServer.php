<?php

namespace App\Models;

use App\Models\BrokerServer;
use App\Models\BrokerServerHost;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class UserBrokerServer extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'user_broker_servers';

    protected $fillable = [
        'broker_server_id', 'user_id','enabled' , 'is_default', 'default_group'
    ];

    public function server_hosts() : HasManyThrough
    {
        return $this->hasManyThrough(BrokerServerHost::class, BrokerServer::class,
            'id', 'broker_server_id', 'broker_server_id', 'id');
    }

    public function broker_server() : BelongsTo
    {
        return $this->belongsTo(BrokerServer::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEnabled($query) {
        return $query->where('enabled', 1);
    }

    public function scopeDefault($query) {
        return $query->where('is_default', 1);
    }

    public function MarkAsDefault() {
        self
            ::whereUserId($this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => 0]);
        $this->is_default = 1;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        // static::created(function ( UserBrokerServer $broker) {
        //     if($broker->is_default) {
        //         UserBrokerServer::whereUserId($broker->user_id)->where('id', '!=', $broker->id)->update(['is_default' => 0]);
        //     }
        // });

        // static::updated(function ( UserBrokerServer $broker) {
        //     if($broker->is_default) {
        //         UserBrokerServer::whereUserId($broker->user_id)->where('id', '!=', $broker->id)->update(['is_default' => 0]);
        //     }
        // });
    }
}
