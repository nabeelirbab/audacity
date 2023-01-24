<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class CopierSignalPageSetting extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_signal_page_settings';
    protected $primaryKey = 'signal_id';

}
