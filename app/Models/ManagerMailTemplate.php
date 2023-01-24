<?php
namespace App\Models;

use App\Exceptions\MissingManagerMailTemplate;
use App\ManagerTemplateMailable;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate;

class ManagerMailTemplate extends MailTemplate implements MailTemplateInterface {

    protected $table = 'mail_templates';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeForManagerMailable(Builder $query, ManagerTemplateMailable $mailable): Builder
    {
        $query->where('mailable', get_class($mailable))
            ->where('manager_id', $mailable->getManagerId());

        if(!is_null($mailable->getTag())) {
            $query->where('tag', $mailable->getTag());
        }

        return $query;
    }

    public static function findForManagerMailable(ManagerTemplateMailable $mailable): self
    {
        $mailTemplate = static::forManagerMailable($mailable)->first();

        if (! $mailTemplate) {
            throw MissingManagerMailTemplate::forManagerMailable($mailable);
        }

        return $mailTemplate;
    }

}