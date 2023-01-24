<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\Portfolio;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Illuminate\Support\Facades\Request;

class PortfolioOrderController extends AdminController
{

    protected function title() {
        return trans('admin.portfolio_order');
    }

    public function index(Content $content)
    {
        $description = '';
        $title = '';
        $portfolioId = Request::get('id');

        if(!empty($portfolioId)) {
            $portfolio = Portfolio::find($portfolioId);

            if($portfolio) {
                $description = $portfolio->description;
                $title = $portfolio->title;
            }
        }
        return $content
            ->title($title)
            ->description($description)
            ->body($this->grid());
    }

    protected function grid()
    {
        return new Grid(new Order(), function (Grid $grid) {
            $accountNumbers = array();

            $portfolioId = Request::get('id');

            if(!empty($portfolioId)) {
                $portfolio = Portfolio::find($portfolioId);

                if($portfolio)
                    $accountNumbers = $portfolio->accounts()->pluck('account_number')->toArray();
            }

            $grid->model()
                ->whereIn('account_number', $accountNumbers)
                ->whereIn('type', [0, 1])
                ->orderBy('status', 'ASC')->orderBy('time_close', 'DESC');

            $grid->column('status')->enum();
            $grid->ticket('Ticket');
            $grid->symbol('Symbol');
            $grid->type_str('Type');
            $grid->lots('Lots');
            $grid->price('Price');
            $grid->price_close('Closed');
            $grid->time_open('Time');
            $grid->time_close('Closed');
            $grid->pl('P/L')->display(function ($pl) {
                if ($pl >= 0) {
                    return "<span style='color: #00a65a; font-weight: bold;'>$pl</span>";
                } else {
                    return "<span style='color: #dd4b39; font-weight: bold;'>$pl</span>";
                }
            });
            $grid->pips('Pips');
            //$grid->comment('Strategy');

            $grid->rows(function ($row) {
                // if ($row->pl >= 0) {
                //     $row->style('color:green');
                // } else {
                //     $row->style('color:red');
                // }
            });

            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchActions();
        });
    }
}
