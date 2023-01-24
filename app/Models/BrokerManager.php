<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class BrokerManager extends Model
{
    use HasDateTimeFormatter;
    protected $table = 'broker_managers';

    public function scopeEnabled()
    {
        $this->whereEnabed(1);
    }
}
