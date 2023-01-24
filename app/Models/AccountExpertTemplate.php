<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\TemplateLoadStatusType;
use App\Jobs\ProcessPendingAccount;
use App\Models\Account;
use App\Models\Expert;
use App\Models\MT4File;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class AccountExpertTemplate extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'expert_templates';

    protected $casts = ['load_status' => TemplateLoadStatusType::class ];

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function template_file()
    {
        return $this->belongsTo(MT4File::class, 'tpl_file_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(static function($query) {
            //$query->automation_file_options = self::parseCredentials($query->options);
        });

        static::saved(function (AccountExpertTemplate $template) {

            $account = Account::find($template->account_id);

            $account->account_status = AccountStatus::PENDING;
            $account->processing = true;
            $account->save();

            ProcessPendingAccount::dispatch($account->id)->onQueue($account->getConnectingQueueName());
        });

        static::creating(function ($query) {
        });
    }
}