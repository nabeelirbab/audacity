<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'targets';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
