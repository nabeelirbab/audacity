<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Collection;

class AddToCopierSubscriptionDestGridExt extends AbstractTool
{
    protected $copiers;

    public function __construct()
    {
        $this->copiers = new Collection();
    }

    public function add($abstract)
    {
        $this->copiers->push($abstract);

        return $this;
    }

    public function render()
    {
        foreach ($this->copiers as $copier) {
            $copier->setResource($this->resource());
            Admin::script($copier->script());
        }

        return view('admin.tools.add2copier', ['copiers' => $this->copiers]);
    }
}
