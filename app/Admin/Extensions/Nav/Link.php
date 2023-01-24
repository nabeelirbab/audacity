<?php

namespace App\Admin\Extensions\Nav;

use Dcat\Admin\Widgets\Tooltip;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

class Link implements Renderable
{
    protected $title;
    protected $href;
    protected $icon;
    protected $tip;
    private   $tipClass;

    public function __construct($title, $href, $tip = FALSE, $icon = null)
    {
        $this->title = $title;
        $this->href = $href;
        $this->icon = $icon;
        $this->tip = $tip;

        $id = Str::random(4);
        $this->href = admin_url($this->href);

        if ($this->icon) {
            $this->icon = "<i class=\"ficon {$this->icon}\"></i>";
        }

        if ($this->title) {
            $this->title = "<span>{$this->title}</span>";
        }

        if ($this->tip) {
            $this->tipClass = 'tt-'.$id;
            Tooltip::make('.'.$this->tipClass)
                ->bottom()
                ->title($this->tip);
        }

    }

    public function render()
    {

        return <<<HTML
<ul class="nav navbar-nav float-right">
    <li class="dropdown dropdown-user nav-item">
        <a class="dropdown-toggle nav-link {$this->tipClass}" href="{$this->href}">
            {$this->icon}
            {$this->title}
        </a>
    </li>
</ul>
HTML;
    }
}
