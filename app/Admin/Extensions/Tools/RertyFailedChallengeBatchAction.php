<?php

namespace App\Admin\Extensions\Tools;

use App\Enums\ChallengeStatus;
use App\Enums\PerformanceStatus;
use App\Models\Challenge;
use App\Models\Performance;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;
use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class RertyFailedChallengeBatchAction extends BatchAction
{
    public function __construct($title=null)
    {
        parent::__construct($title);
    }

    public function handle(Request $request)
    {

        foreach (Challenge::where('status', ChallengeStatus::ERROR)->find($this->getKey()) as $challenge) {

            $challenge->status = ChallengeStatus::CONFIRMED;
            $challenge->save();
        }

        return $this->response()
            ->success(trans('admin.retry_success'))
            ->refresh();
    }

    protected function parameters()
    {
        return [];
    }

}
