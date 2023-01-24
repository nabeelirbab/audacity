<?php

namespace App\Admin\Controllers;

use App\Enums\MT4Timeframe;
use App\Enums\TemplateLoadStatusType;
use App\Models\Account;
use App\Models\AccountExpertTemplate;
use App\Models\BrokerSymbol;
use App\Models\Expert;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Illuminate\Support\Facades\Request;

class AccountExpertTemplateController extends AdminController
{
    protected $translation = 'account-expert-template';

    protected function detail($id)
    {
        $show = new Show(AccountExpertTemplate::findOrFail($id));

        $show->symbol('Symbol');
        $show->timeframe('Timeframe');
        $show->options('Options');
        $show->snapshot('Snapshot');

        $show->created_at('Created');
        $show->updated_at('Updated');

        return $show;
    }

    protected function grid()
    {
        return new Grid( AccountExpertTemplate::with(['account', 'expert']), function (Grid $grid) {
            $grid->id('ID');

            $grid->model()
                ->whereHas('account', static function ($q) {
                    $q->whereManagerId(Admin::user()->id);
                });

            $grid->column('account.account_number', ___('account'));
            $grid->column('expert.name');
            $grid->column('symbol');
            $grid->column('timeframe');

            $grid->column('enabled')->switch();

            $grid->column('load_status')->enum();

            $grid->updated_at('Updated')->display(function ($updated_at) {
                return Carbon::parse($updated_at)->diffForHumans();
            });

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
            });

            $grid->actions(function ($actions) {
                //$actions->disableView();
            });

            $grid->tools(function ($tools) {
                //if(!empty($this->expertId))
                    //$tools->append(new AddExpertAccountButton($this->expertId));
            });
        });
    }

    protected function form()
    {
        return new Form(AccountExpertTemplate::with(['account', 'expert']), function (Form $form) {

            $form->display('id', 'ID');

            $accounts = Account::whereManagerId(Admin::user()->id)->pluck('account_number', 'id');
            $form->select('account_id', 'Account')->options($accounts)->required();

            $symbols = BrokerSymbol::all()->pluck('name', 'name');
            $form->select('symbol', 'Symbol')->options($symbols)->required();

            $form->select('timeframe', 'TimeFrame')->options(MT4Timeframe::map())->required();

            $experts = Expert::whereManagerId(Admin::user()->id)->pluck('name', 'id');
            $expertTemplates = Expert::whereManagerId(Admin::user()->id)->pluck('template_default', 'id');

            $s = $form->select('expert_id')->options($experts)->required();

            foreach($expertTemplates as $id => $template) {
                $s ->when($id, function(Form $form) use($id, $template) {
                    $form->textarea('_'.$id, ___('template'))->value($template);
                    $form->ignore(['_'.$id]);
                });
            }

            $form->textarea('options', 'Options')->required();

            $form->switch('enabled', 'Enabled?')->default(1);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    private function prepare()
    {
        $this->expertId = Request::get('expert_id');
        $experts = array();

        $items = Expert::whereManagerId(Admin::user()->id)->get();

        foreach ($items as $item) {
            $expert['title'] = $item->name;
            $expert['link'] = url()->current() . '?expert_id=' . $item->id;

            if (empty($this->expertId) || $this->expertId == 0) {
                $this->expertId = $item->id;
                $expert['active'] = true;
            } else {
                $expert['active'] = $this->expertId == $item->id;
            }

            $experts[] = $expert;
        }

        $vars = [
            'experts'       => $experts,
            'panel'          => $this->grid(),
        ];

        return view('admin.experts', $vars);
    }
}