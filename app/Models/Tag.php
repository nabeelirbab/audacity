<?php

namespace App\Models;

use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'tags';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
