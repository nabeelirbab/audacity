<?php

namespace App\Models;

use App\Models\MT4File;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'experts';

    public function ex4_file()
    {
        return $this->belongsTo(MT4File::class, 'ex4_file_id');
    }

}
