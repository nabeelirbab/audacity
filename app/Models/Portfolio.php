<?php

namespace App\Models;

use App\Models\Account;
use App\Models\AccountStat;
use App\Enums\CopierType;
use App\Models\PortfolioStat;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Portfolio extends Model
{
    use HasDateTimeFormatter;

    private $cached = false;
    private $drawdown = 0;
    private $nofOrders = 0;
    private $profit = 0;
    private $totalLots = 0;
    private $balance = 0;

    protected $appends = [
        'drawdown',
        'countOrders',
        'profit',
        'totalLots',
        'balance'
    ];

    protected $table = 'portfolios';

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function stat() : HasOne
    {
        return $this->hasOne(PortfolioStat::class);
    }

    public function accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'portfolio_accounts', 'portfolio_id', 'account_id');
    }

    public function getTotalLotsAttribute() : int
    {

        $this->_calcStat();

        return $this->totalLots;
    }

    public function getBalanceAttribute() : float
    {

        $this->_calcStat();

        return $this->last_balance + $this->balance;
    }

    public function getDrawdownAttribute()
    {

        $this->_calcStat();

        return $this->drawdown;
    }

    public function getCountOrdersAttribute() : int
    {
        $this->_calcStat();

        return $this->nofOrders;
    }

    public function getProfitAttribute()
    {
        $this->_calcStat();

        return $this->profit;
    }

    private function _calcStat() {

        if($this->cached)
            return;

        $dd = array();
        $accounts = $this->accounts()->get();

        if($accounts->count() < 1) {
            $this->cached = true;
            return 0;
        }

        foreach($accounts as $account) {

            /** @var Account $account */

            if($account->copier_type != CopierType::STRATEGY)
                AccountStat::calcStat($account->account_number);

            /** @var AccountStat $stat */
            $stat = $account->stat()->first();

            if(!is_null($stat))
                continue;

            $this->nofOrders += $stat->nof_closed;
            $this->profit += $stat->profit;
            $this->totalLots += $stat->total_lots;
            $this->balance += $stat->balance;

            $d = $stat->drawdown_perc;
            $dd[] = $d;
        }

        if(count($dd) > 0) {
            $this->drawdown = max($dd);
        }

        $this->cached = true;
    }

}
