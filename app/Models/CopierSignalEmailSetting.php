<?php

namespace App\Models;

use App\Models\CopierSignal;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class CopierSignalEmailSetting extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'copier_signal_email_settings';
    protected $primaryKey = 'signal_id';
    public $timestamps = false;

    public function signal()
    {
        return $this->belongsTo(CopierSignal::class, 'signal_id');
    }

}
