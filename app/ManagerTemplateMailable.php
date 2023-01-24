<?php

namespace App;

use App\Models\ManagerMailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use ReflectionClass;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\TemplateMailable;

class ManagerTemplateMailable extends TemplateMailable
{
    use Queueable, SerializesModels;

    private $managerId;
    private $tag;
    private $layout;

    public function __construct($managerId, $tag = null) {
        $this->managerId = $managerId;
        $this->tag = $tag;
    }

    public static function getVariableDefaults(): array
    {
        $class = new ReflectionClass(static::class);
        $publics = self::getVariables();

        return collect($class->getDefaultProperties())
            ->filter(function($value, $key) use($publics) {
                return in_array($key, $publics);
            })
            ->values()
            ->all();
    }

    public static function makeWithFakeData(string $subject, string $template, int $managerId) {
        $options = self::getVariableDefaults();
        $options[] = $managerId;

        $obj = new static(...$options);
        $obj->mailTemplate = new MailTemplateFake($subject, $template);

        return $obj;
    }

    public function getManagerId(): int
    {
        return $this->managerId;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function getHtmlLayout(): string
    {
        if(!empty($this->layout))
            return $this->layout;

        try {
            return view('emails.layout', [])->render();
        } catch (\Throwable $e) {
        }

        return "{{{body}}}";
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    protected function resolveTemplateModel(): MailTemplateInterface
    {
        return $this->mailTemplate = ManagerMailTemplate::findForManagerMailable($this);
    }
}
