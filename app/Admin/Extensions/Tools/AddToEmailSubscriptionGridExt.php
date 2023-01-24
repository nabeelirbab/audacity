<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Collection;

class AddToEmailSubscriptionGridExt extends AbstractTool
{
    protected $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new Collection();
    }

    public function add($abstract)
    {
        $this->subscriptions->push($abstract);

        return $this;
    }

    public function render()
    {
        foreach ($this->subscriptions as $subscription) {
            $subscription->setResource($this->resource());
            Admin::script($subscription->script());
        }

        return view('admin.tools.add2email_subscription', ['subscriptions' => $this->subscriptions]);
    }
}
