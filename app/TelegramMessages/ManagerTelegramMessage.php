<?php

namespace App\TelegramMessages;

use App\Helpers\BladeHelper;
use App\Models\TelegramMessageTemplate;
use Exception;
use Illuminate\Bus\Queueable;
use NotificationChannels\Telegram\TelegramMessage;
use ReflectionClass;
use ReflectionProperty;

class ManagerTelegramMessage extends TelegramMessage {

    protected int $manager_id;

    public function __construct(int $manager_id) {
        $this->manager_id = $manager_id;

        parent::__construct($this->buildMessage());
    }

    private function buildMessage() : string {

        $template = TelegramMessageTemplate::where('telegramable', get_called_class())->whereManagerId($this->manager_id)->first();

        if(!$template) {
            $class = get_called_class();
            throw new Exception("TelegramMessageTemplate not found for class {$class}");
        }

        $data = [];
        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== self::class) {
                $data[$property->getName()] = $property->getValue($this);
            }
        }

        $message = BladeHelper::bladeCompile( $template->markdown_template, $data);

        return $message;
    }
}