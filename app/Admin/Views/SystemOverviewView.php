<?php

namespace App\Admin\Views;

use App\Admin\Views\AccountsOverviewInfoBox;
use App\Admin\Views\Monitoring\CPULine;
use App\Admin\Views\Monitoring\DiskspaceLine;
use App\Admin\Views\Monitoring\MemoryLine;
use App\Admin\Views\ReportOverviewInfoBox;
use App\Admin\Views\SignalsOverviewInfoBox;
use App\Admin\Views\SystemLogsOverviewInfoBox;
use App\Admin\Views\UsersOverviewInfoBox;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Row;
use Illuminate\Contracts\Support\Renderable;

class SystemOverviewView implements Renderable
{

    public function render() {

        $row1 = tap(new Row, function ($row) {
            $row->column(3, (new UsersOverviewInfoBox('', 'white', __('admin.users'), 'users', admin_url('clients')))->padding('15px'));
            $row->column(3, (new AccountsOverviewInfoBox('', 'white', __('admin.accounts'), 'briefcase', admin_url('accounts')))->padding('15px'));
            $row->column(3, (new SystemLogsOverviewInfoBox('', 'white', __('system-log.system_logs'), 'cog', admin_url('system-logs')))->padding('15px'));
            $row->column(3, (new ReportOverviewInfoBox('', 'white', __('admin.balance'), 'signal', admin_url('accounts')))->padding('15px'));
        });

        $row2 = tap(new Row, function ($row) {
            if(Admin::user()->can('mng.copiers'))
                $row->column(3, (new SignalsOverviewInfoBox('', 'white', __('admin.followers'), 'user-plus ', admin_url('followers')))->padding('15px'));
            $row->column(3, (new CPULine('CPU'))->padding('15px'));
            $row->column(3, (new MemoryLine('RAM'))->padding('15px'));
            $row->column(3, (new DiskspaceLine('Disk'))->padding('15px'));
            //$row->column(3, (new SignalsChart)->padding('15px'));
        });

        $content = $row1->render();
        $content .= $row2->render();

        return $content;
    }

}
