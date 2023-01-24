<?php

namespace App\Admin\Controllers;

use App\Admin\Views\AccountsView;
use App\Admin\Views\CopierErrorsView;
use App\Admin\Views\HistoryView;
use App\Admin\Views\SystemOverviewView;
use App\Admin\Views\TradesView;
use App\Models\Order;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Routing\Controller;

class SystemDashboardController extends Controller
{

    public function index(Content $content)
    {
        $tab = new Tab();
        $active = false;

        if (Admin::user()->can('mng.view_overview')) {
            $overview = new SystemOverviewView();
            $tab->add(___('overview'), $overview, !$active);
            $active = true;
        }

        if (Admin::user()->can('mng.view_accounts')) {
            $accounts = new AccountsView();
            $tab->add(___('accounts'), $accounts, !$active);
            $active = true;
        }

        if (Admin::user()->can('mng.view_trades')) {
            $trades = new TradesView();
            $tab->add(___('trades'), $trades, !$active);
            $active = true;
        }

        if (Admin::user()->can('mng.view_trades_history')) {
            $history = new HistoryView();
            $tab->add(___('history'), $history, !$active);
            $active = true;
        }

        if (Admin::user()->can('mng.copiers') && Admin::user()->can('mng.view_copier_errors')) {
            $errors = new CopierErrorsView();
            $tab->add(___('errors', ['nof_errors' => Order::getCountErrors(Admin::id())]), $errors, !$active);
            $active = true;
        }

        $content->body($tab);

        return $content;
    }
}

