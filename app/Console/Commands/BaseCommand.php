<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BaseCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    public function error($message, $context = [])
    {
        Log::error('console. ' . $this->signature . '. ' . $message, $context);
    }

    public function warning($message, $context = [])
    {
        Log::warning('console. ' . $this->signature . '. ' . $message, $context);
    }

    public function info($message, $context = [])
    {
        Log::info('console. ' . $this->signature . '. ' . $message, $context);
    }

    public function critical($message, $context = [])
    {
        Log::critical('console. ' . $this->signature . '. ' . $message, $context);
    }

    public function emergency($message, $context = [])
    {
        Log::emergency('console. ' . $this->signature . '. ' . $message, $context);
    }
}
