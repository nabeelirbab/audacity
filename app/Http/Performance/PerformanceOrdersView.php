<?php

namespace App\Http\Performance;

use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class PerformanceOrdersView extends LazyRenderable
{
    private $performance;

    public function __construct($performance) {
        $this->performance = $performance;
    }

    public function grid(): Grid
    {

        return new Grid($this->performance->ordersClosed(), function(Grid $grid ) {
            $grid->model()->orderBy('time_close', 'DESC');
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->title('Closed Orders');

            $grid->column('ticket', 'Ticket');
            $grid->column('time_open','Open');
            $grid->column('type_str','Type');
            $grid->column('lots','Vol');
            $grid->column('symbol','Symbol');
            $grid->column('price','Price');
            $grid->column('stoploss','SL');
            $grid->column('takeprofit','TP');
            $grid->column('time_close','Close');
            $grid->column('price_close','Price');
            $grid->column('swap','Swap');
            $grid->column('pl','P/L');

            $grid->paginate(10);
        });
    }

}
