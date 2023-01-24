<?php

namespace App\Exceptions;

use App\ManagerTemplateMailable;
use Exception;

class MissingManagerMailTemplate extends Exception
{
    public static function forManagerMailable(ManagerTemplateMailable $mailable)
    {
        $mailableClass = class_basename($mailable);
        $managerId = $mailable->getManagerId();
        $tag = $mailable->getTag();

        return new static("No mail template exists for mailable `{$mailableClass}`, manager=`$managerId`, tag={$tag}.");
    }
}
