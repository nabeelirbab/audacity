<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Grid\Tools\AbstractTool;


class AddFollowerAccountButton extends AbstractTool
{
    public $signalId;

    public function __construct($signalId)
    {
        $this->signalId = $signalId;
    }

    public function render()
    {
        $new = __('copier.new_follower');

        return <<<EOT

        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="{$this->resource()}/create?&signal_id={$this->signalId}" class="btn btn-primary btn-outline">
        <i class="fa fa-plus"></i>&nbsp;&nbsp;{$new}
    </a>
</div>

EOT;
    }
}
