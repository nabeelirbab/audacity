<?php

namespace App\Admin\Forms;

use App\Enums\CopierRiskType;
use App\Models\CopierSignalFollower;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class FollowerRiskEditForm extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle(array $input)
    {
        $line = CopierSignalFollower::find($this->payload['key']);

        $line->update($input);

        return $this->response()->success(__('admin.update_succeeded'))->refresh();
    }

    public function form()
    {
        $this->display('account.account_number', 'account');
        $this->display('signal.title','signal');

        $options = CopierRiskType::map();

        $this->radio('risk_type')
            ->when(CopierRiskType::SCALING->value, function (Form $form) {
                $form->decimal('scaling_factor')->default(1);
            })
            ->when(CopierRiskType::MULTIPLIER->value, function (Form $form) {
                $form->decimal('lots_multiplier')->default(1);
            })
            ->when(CopierRiskType::FIXED_LOT->value, function (Form $form) {
                $form->decimal('fixed_lot')->default(0.1);
            })
            ->when(CopierRiskType::RISK_PERCENT->value, function (Form $form) {
                $form->decimal('max_risk')->default(3);
            })
            ->when(CopierRiskType::MONEY_RATIO->value, function (Form $form) {
                $form->decimal('money_ratio_lots')->default(0.1);
                $form->decimal('money_ratio_dol')->default(500);
            })
            ->options($options)
            ->default(CopierRiskType::MULTIPLIER);

    }

    public function default()
    {

        $line = CopierSignalFollower::with(['account:id,account_number', 'signal:id,title'])
            ->find($this->payload['key'],
            [
                'risk_type', 'scaling_factor', 'lots_multiplier', 'fixed_lot',
                'max_risk', 'money_ratio_lots', 'money_ratio_dol', 'account_id', 'signal_id'
            ]
            );
        return $line->toArray();
    }
}
