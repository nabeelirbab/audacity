<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Actions\Action;

class DetailsLinkButton extends Action
{
    private $url;
    private $icon;

    public function __construct($title, $icon, $url)
    {
        $this->url = $url;
        $this->icon = $icon;
        parent::__construct($title);
    }

    protected function html()
    {
        $this->appendHtmlAttribute('class', ' btn btn-sm btn-primary');
        $this->defaultHtmlAttribute('href', $this->url);

        return <<<HTML
        <div class="btn-group pull-right btn-mini" style="margin-right: 5px">
    <a {$this->formatHtmlAttributes()}>
        <i class="fa {$this->icon}"></i>&nbsp;&nbsp;{$this->title()}
    </a>
</div>
HTML;
    }
}
