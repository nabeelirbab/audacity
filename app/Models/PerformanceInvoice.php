<?php

namespace App\Models;

use App\Models\Challenge;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class PerformanceInvoice extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'performance_invoices';

    public function order() {
        return $this->belongsTo(Challenge::class);
    }

}
