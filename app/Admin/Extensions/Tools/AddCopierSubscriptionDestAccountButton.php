<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Grid\Tools\AbstractTool;


class AddCopierSubscriptionDestAccountButton extends AbstractTool
{
    public $copier_subscription_id;

    public function __construct($copier_subscription_id)
    {
        $this->copier_subscription_id = $copier_subscription_id;
    }

    public function render()
    {
        $new = 'New Follower';

        return <<<EOT

<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="{$this->resource()}/create?&copier_subscription_id={$this->copier_subscription_id}" class="btn btn-sm btn-success">
        <i class="fa fa-plus"></i>&nbsp;&nbsp;{$new}
    </a>
</div>

EOT;
    }
}
