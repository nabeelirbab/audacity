<?php

namespace App\Models;

use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class VideoPost extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'video_posts';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopePublic($query) {
        return $query->where('is_public', 1);
    }

}
