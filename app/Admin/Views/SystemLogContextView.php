<?php

namespace App\Admin\Views;

use App\Models\SystemLog;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Dump;

class SystemLogContextView extends LazyRenderable
{

    public function __construct($id = null, array $payload = []) {

        $this->payload(['log_id' => $id]);
        parent::__construct($payload);
    }

    public function render()
    {
        return new Dump(json_encode(SystemLog::findOrFail($this->log_id)->context));
    }
}
