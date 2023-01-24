<?php

namespace App\Interfaces;

//use Illuminate\Contracts\Mail\Mailable;

interface TelegramMessageTemplateInterface
{
//    public static function findForMailable(Mailable $mailable);

    /**
     * Get the template.
     *
     * @return string
     */
    public function getHtmlTemplate(): string;

    /**
     * Get the template.
     *
     * @return null|string
     */
    public function getMarkdownTemplate(): ?string;
}
