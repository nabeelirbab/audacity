<?php

namespace App\Admin\Extensions\Tools;

use App\Admin\Forms\FollowerRiskBulkEditForm;
use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\Widgets\Modal;

class UpdateFollowerRiskBatchAction extends BatchAction
{

    private $signalId;

    public function __construct($title, $signalId)
    {
        $this->signalId = $signalId;
        parent::__construct($title);
    }

    public function render()
    {
        $form = new FollowerRiskBulkEditForm($this->signalId);

        return Modal::make()
            ->lg()
            ->title($this->title)
            ->body($form)
            ->onLoad($this->getModalScript())
            ->button($this->title);
    }

    protected function getModalScript()
    {
        return <<<JS
var key = {$this->getSelectedKeysScript()}
$('input[name=follower-ids]:hidden').val(key);
JS;
    }
}
