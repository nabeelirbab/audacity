<?php

namespace App\Models;

use App\Enums\MT4FileType;
use App\Models\BrokerSymbol;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MT4File extends Model
{
    protected $table = 'files';

    use HasDateTimeFormatter;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function(MT4File $file) {
            $storage = Storage::disk(config('admin.upload.disk'));
            $storage->delete($file->path);
        });
    }
}
