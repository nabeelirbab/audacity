<?php

namespace App\Models;

use App\Enums\BrokerServerHostStatus;
use App\Models\BrokerServer;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerServerHost extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'broker_server_hosts';

    protected $fillable = [
        'host', 'port', 'ping', 'is_main', 'broker_server_id'
    ];

    protected $casts = ['status' => BrokerServerHostStatus::class];

    public function brokerServer() : BelongsTo
    {
        return $this->belongsTo(BrokerServer::class, 'broker_server_id');
    }

}
