<?php

namespace App;

use Illuminate\Contracts\Mail\Mailable;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;

class MailTemplateFake implements MailTemplateInterface
{

    private string $subject;
    private string $template;

    public function __construct(string $subject, string $template)
    {
      $this->subject = $subject;
      $this->template = $template;
    }

    public static function findForMailable(Mailable $mailable) {
        return null;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    /**
     * Get the mail template.
     *
     * @return string
     */
    public function getHtmlTemplate(): string {
        return $this->template;
    }

    /**
     * Get the mail template.
     *
     * @return null|string
     */
    public function getTextTemplate(): ?string {
        return null;
    }
}