<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\PerformanceStatus;
use App\Models\Performance;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class ResetTargetBatchAction extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {

        foreach (Performance::find($this->getKey()) as $perf) {

            PerformanceTarget
                ::find($perf->id)
                ->reset();

            PerformanceStat
                ::find($perf->id)
                ->reset();

            $perf->status = PerformanceStatus::CALCULATING;
            $perf->save();
        }

        return $this->response()
            ->success(trans('admin.reset_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
