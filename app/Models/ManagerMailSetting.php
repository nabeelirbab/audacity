<?php
namespace App\Models;

use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ManagerMailSetting extends Model {

    use HasDateTimeFormatter;

    protected $primaryKey = "manager_id";
    public $incrementing = false;

    protected $table = 'mail_settings';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}