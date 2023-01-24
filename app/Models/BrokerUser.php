<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class BrokerUser extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'broker_users';
}
