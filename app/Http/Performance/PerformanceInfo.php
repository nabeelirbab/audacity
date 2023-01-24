<?php

namespace App\Http\Performance;

use App\Models\Performance;
use Carbon\Carbon;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Table;

class PerformanceInfo extends Box
{

    public function __construct( $slug, Performance $performance) {
        $url = admin_url('metrics/'.$slug);
        $data[] = ['Started', Carbon::parse($performance->started_at)->format('Y-m-d')];
        if(!is_null($performance->ended_at))
            $data[] = ['Ended', Carbon::parse($performance->ended_at)->format('Y-m-d')];
        else
            $data[] = ['Ended', '-'];
        $data[] = ['Share', "<a href=\"$url\" target=\"_blank\">Url</a>"];

        $table = new Table([], $data);

        parent::__construct( trans('trading-objectives.status'), $table->render() );
    }
}
