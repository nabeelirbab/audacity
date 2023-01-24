<?php

namespace App\Admin\Views;

use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\InfoBox;
use Illuminate\Support\Facades\Cache;

class UsersOverviewInfoBox extends InfoBox
{

    public function render()
    {

        $users = Cache::get('online-users');

        $online = 0;

        if($users) {
            $online = count($users);
        }

        $total = User::whereManagerId(Admin::user()->id)->count();


        $this->withContent($online, $total);

        return parent::render();
    }

    protected function withContent($online, $total)
    {
        $titleOnline = trans('admin.online');
        $titleTotal = trans('admin.total');

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="margin-bottom: 8px">
    <div style="width: 80px">
        <i class="fa fa-circle text-success"></i> {$titleOnline}
    </div>
    <div>{$online}</div>
</div>
<div class="d-flex pl-1 pr-1" style="margin-bottom: 8px">
    <div style="width: 80px">
        <i class="fa fa-circle text-primary"></i> {$titleTotal}
    </div>
    <div>{$total}</div>
</div>
HTML
        );
    }
}