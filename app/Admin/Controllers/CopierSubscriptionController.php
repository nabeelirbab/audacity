<?php

namespace App\Admin\Controllers;

use App\Enums\FilterCondition;
use App\Models\Account;
use App\Enums\CopierRiskType;

use App\Models\CopierSubscription;
use App\Enums\CopierType;
use App\Models\ManagerSetting;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Illuminate\Support\Str;

class CopierSubscriptionController extends AdminController
{

    protected function title()
    {
        return trans('admin.copier_subscription');
    }

    protected function detail($id)
    {
        $show = new Show(CopierSubscription::findOrFail($id));

        $show->lots_multiplier('Multiplier');
        $show->fixed_lot('Fixed Lot');
        $show->money_ratio_lots('Ratio Lots');
        $show->money_ratio_dol('Ratio Dollars');
        $show->comment('Comment');

        $show->enabled('Trade Allowed?')->as(function ($val) {
            return $val == 1 ? 'Yes' : 'No';
        });

        $show->created_at('Created');
        $show->updated_at('Updated');

        return $show;
    }

    protected function grid()
    {
        return new Grid(new CopierSubscription(), function (Grid $grid) {
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id('ID');

            $grid->slug('Key');
            $grid->column('title','Title');
            $grid->comment('Comment');
            $grid->sources('Sender Accounts')->pluck('account_number')->label();
            //$grid->min_balance('Min Balance')->editable();

            $grid->column('risk_type')->display(fn( CopierRiskType $risk) => $risk->shortTitle());

            $grid->scaling_factor('Scaling Factor')
                ->display(function ($val) {
                    if ($this->risk_type != CopierRiskType::SCALING) {
                        return '';
                    }

                    return $val;
                });

            $grid->lots_multiplier('Multiplier')
                ->display(function ($val) {
                    if ($this->risk_type != CopierRiskType::MULTIPLIER) {
                        return '';
                    }

                    return $val;
                });
            $grid->fixed_lot('Fixed Lots')
                ->display(function ($val) {
                    if ($this->risk_type != CopierRiskType::FIXED_LOT) {
                        return '';
                    }

                    return $val;
                });
            $grid->max_risk('Risk (%)')
            ->display(function ($val) {
                if ($this->risk_type != CopierRiskType::RISK_PERCENT) {
                    return '';
                }

                return $val;
            });

            $grid->actions(function ($actions) {
                $actions->disableView();
            });

            $grid->column('is_public')->switch()->sortable();
            $grid->created_at();
            $grid->updated_at();

            $grid->showColumnSelector();
            $grid->hideColumns(['created_at', 'updated_at']);

            $mSettings = ManagerSetting::getCurrent();

            if($mSettings && !$mSettings->canHaveCopiers()) {
                $grid->disableCreateButton();
            }

            $grid->filter(function ($filter) {
                $filter->like('title');
                $filter->equal('sources.account_id', 'Sender Account')->select(
                    Account::whereManagerId(Admin::user()->id)->where('copier_type', CopierType::SENDER)->pluck('account_number', 'id')
                );
                $filter->disableIdFilter();
            });
            $grid->disableRefreshButton();

        });
    }

    protected function form($id = false)
    {
        return new Form(new CopierSubscription(), function (Form $form) use($id) {
            $form->hidden('manager_id')->value(Admin::user()->id);
            $form->hidden('creator_id')->value(Admin::user()->id);

            if ($form->isEditing()) {
                $form->text('slug', 'Key')->disable();
            } else {
                $form->text('slug', 'Key')->creationRules(['required', "unique:copier_subscriptions"]);
            }

            $form->text('title', 'Title')->required();

            $form->multipleSelect('sources', 'Sender Accounts')->options(
                Account::where([
                    ['manager_id', Admin::user()->id],
                    ['copier_type', CopierType::SENDER]
                ])
                    ->selectRaw("CONCAT (`account_number`,
                    CASE WHEN title IS NULL THEN '' ELSE CONCAT(' (',`title`,')') END  ) AS acc_title, id")
                    ->pluck('acc_title', 'id')
            )->required()
            ->customFormat(function ($v) {
                return array_column($v, 'id');
            })
            ->help('To add Sender Accounts, go to <a href="/caccounts">Accounts Page</a>');

            $form->number('min_balance', 'Min Balance')->default(1);
            $form->number('max_lots_per_trade', 'Max Lots Per Trade')->min(0.01)->required()->default(1);

            $form->radio('risk_type', 'Risk Type')
                ->when(CopierRiskType::SCALING, function(Form $form) {
                    $form->number('scaling_factor', 'Scaling Factor')->default(1);
                } )
                ->when(CopierRiskType::MULTIPLIER, function(Form $form) {
                    $form->number('lots_multiplier', 'Lots Multiplier')->default(1);
                } )
                ->when(CopierRiskType::FIXED_LOT, function(Form $form) {
                    $form->decimal('fixed_lot', 'Fixed Lot')->default(0.1);
                } )
                ->when(CopierRiskType::RISK_PERCENT, function(Form $form) {
                    $form->number('max_risk', 'Risk (%)')->default(3);
                } )
                ->when(CopierRiskType::MONEY_RATIO, function(Form $form) {
                    $form->decimal('money_ratio_lots', 'Ratio Lots')->default(0.1);
                    $form->decimal('money_ratio_dol', 'Ratio Dollars')->default(500);
                } )
                ->options(CopierRiskType::map())
                ->default(CopierRiskType::MULTIPLIER);

            $form->text('comment', 'Comment')->help('Comment line for the follower orders');
            $form->textarea('pairs_matching', 'Pairs Matching')->help('Format is <b>GOLD=AUUSD;SILVER=AGUSD;</b>');

            if(config('copier.has_adv_filters')) {
                $conditions = FilterCondition::map();

                $form->select('filter_symbol_condition', 'Filter Symbol Condition')->options($conditions);
                $form->text('filter_symbol_values', 'Filter Symbol Values');

                $form->select('filter_magic_condition', 'Filter Magic Condition')->options($conditions);
                $form->text('filter_magic_values', 'Filter Magic Values');

                // $form->select('filter_comment_condition', 'Filter Comment Condition')->options($conditions);
                // $form->text('filter_comment_values', 'Filter Comment Values');
            }

            $form->number('price_diff_accepted_pips', 'Price Diff')->default(50)->help('If price difference is more than Price Diff, order will be ignored. Works for account which connected when Sender has open orders.');
            $form->switch('is_public', 'Public?')->default(0);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                if ( !$form->isEditing() && empty($form->slug)) {
                    $form->slug = Str::slug($form->title, '-');
                }
            });
        });
    }
}
