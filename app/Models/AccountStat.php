<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Account;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class AccountStat extends Model
{
    const TABLE = 'account_stats';
    const KEY = 'account_number';

    protected $table = self::TABLE;

    protected $primaryKey = self::KEY;

    protected $fillable = [
        'balance', 'nof_closed', 'nof_working', 'currency', 'equity', 'profit', 'credit', 'name'
    ];

    public function scopeDeposited($query) {
        return $query->where('deposit', '!=', 0);;
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, self::KEY, self::KEY);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class, self::KEY, self::KEY);
    }

    public function isAllOrdersUploaded() : bool
    {
        return !$this->orders()->whereStatus(OrderStatus::CLOSED)->whereNull('time_close')->exists();
    }

    public function reset() {
        $this->nof_processed = 0;
        $this->save();
    }

    public static function calcFiltered($accountNumber, $from, $to) : AccountStat
    {
        $tbl = self::TABLE;
        $key = self::KEY;

        $stat = AccountStat::find($accountNumber, ['account_number','balance', 'equity']);

//        $stat->account_number = $accountNumber;
        $stat->first_trade_day = $from;
        $stat->last_trade_day = $to;

        $filter = " $key=$accountNumber ";
        $timeFilter = '';
        if(!is_null($to))
            $timeFilter .= " and time_close <= '$to' ";

        if(!is_null($from))
            $filter .= " and time_close >= '$from' ";

        if(!is_null($to))
            $filter .= " and time_close <= '$to' ";

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE $filter and type not in (6,7) and status=3";
        $stat->nof_processed = DB::selectOne($q)->val;

        $q = "SELECT(IFNULL((SELECT sum(pl) FROM account_orders WHERE type=6 and status=3 and ( comment like 'Commission - %' or comment like '%Rollover%' ) and $filter), 0)".
		    "+(SELECT sum(commission) FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $filter)) val";
        $stat->total_commission = DB::selectOne($q)->val;

        $q = "SELECT sum(pl+swap) val FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $filter";
        $stat->total_profit = DB::selectOne($q)->val;
        $stat->total_profit += $stat->total_commission;

        $q = "SELECT sum(pl) val FROM `account_orders` WHERE (type=6 or type=7) and pl > 0 and comment not like '%Commission%' and comment not like '%Rollover%' and status=3 and $key=$accountNumber $timeFilter";
        $stat->deposit = DB::selectOne($q)->val;

        $stat->gain_perc = 0;
        if($stat->deposit != 0)
            $stat->gain_perc = $stat->total_profit/$stat->deposit*100.0;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE $filter and pl>=0 and (type=0 or type=1) and status=3";
        $stat->nof_won = DB::selectOne($q)->val;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE $filter and pl<0 and (type=0 or type=1) and status=3";
        $stat->nof_lost = DB::selectOne($q)->val;

        $stat->win_ratio = 0;
        if($stat->nof_processed != 0)
            $stat->win_ratio = $stat->nof_won/($stat->nof_processed)*100;

        $stat->loss_ratio = 0;
        if($stat->nof_processed != 0)
            $stat->loss_ratio = $stat->nof_lost/($stat->nof_processed)*100;

        $q = "SELECT CASE WHEN FLOOR(DATEDIFF(max(time_close), min(time_close))) = 0 THEN 1 ELSE FLOOR(DATEDIFF(max(time_close), min(time_close))) END val FROM `account_orders` WHERE (type=0 or type=1) and status=3 and $filter";
        $stat->total_days = DB::selectOne($q)->val;

        $stat->daily_perc = 0;
        if($stat->total_days != 0)
            $stat->daily_perc = $stat->gain_perc/$stat->total_days;

        $q = "SELECT avg(pl) val FROM `account_orders` WHERE (type=0 or type=1) and pl>=0 and status=3 and $filter";
        $stat->avg_win = DB::selectOne($q)->val;

        $q = "SELECT avg(pl) val FROM `account_orders` WHERE (type=0 or type=1) and pl<0 and status=3 and $filter";
        $stat->avg_loss = DB::selectOne($q)->val;

        $q = "SELECT sum(lots) val FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $filter";
        $stat->total_lots = DB::selectOne($q)->val;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE (type=0) and status=3 and $filter";
        $stat->total_longs = DB::selectOne($q)->val;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE (type=0) and pl>=0 and status=3 and $filter";
        $stat->longs_won = DB::selectOne($q)->val;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE (type=1) and status=3 and $filter";
        $stat->total_shorts = DB::selectOne($q)->val;

        $q = "SELECT count(ticket) val FROM `account_orders` WHERE (type=1) and pl>=0 and status=3 and $filter";
        $stat->shorts_won = DB::selectOne($q)->val;

        $q = "SELECT max(pl) val FROM `account_orders` WHERE type not in (6,7) and $filter and status=3";
        $stat->best_trade_dol = DB::selectOne($q)->val;

        $q = "SELECT min(pl) val FROM `account_orders` WHERE type not in (6,7) and $filter and status=3";
        $stat->worst_trade_dol = DB::selectOne($q)->val;

        $stat->avg_trades_per_day = 0;
        if($stat->total_days != 0)
            $stat->avg_trades_per_day = $stat->nof_processed/$stat->total_days;

        $stat->avg_profit_per_day = 0;
        if($stat->total_days != 0)
            $stat->avg_profit_per_day = $stat->total_profit/$stat->total_days;

        $stat->current_pace = $stat->daily_perc*365;

        return $stat;
    }

    public function refresh() : AccountStat {
        $tbl = self::TABLE;
        $key = self::KEY;
        $accountNumber = $this->account_number;

        $q = "update $tbl set nof_processed=(SELECT count(ticket) FROM `account_orders` WHERE $key=$accountNumber and type not in (6,7) and status=3 ) WHERE $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_commission=".
	        "IFNULL((SELECT sum(pl) FROM account_orders WHERE type=6 and status=3 and ( comment like 'Commission - %' or comment like '%Rollover%' ) and $key=$accountNumber), 0)".
		    "+(SELECT sum(commission) FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_profit=total_commission+(SELECT sum(pl+swap) FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set deposit=(SELECT sum(pl) FROM `account_orders` WHERE (type=6 or type=7) and pl > 0 and comment not like '%Commission%' and comment not like '%Rollover%' and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET gain_perc = case when deposit=0 then 0 else total_profit/(deposit)*100 end where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set nof_won=(SELECT count(ticket) FROM `account_orders` WHERE $key=$accountNumber and pl>=0 and (type=0 or type=1) and status=3) ,
            nof_lost=(SELECT count(ticket) FROM `account_orders` WHERE $key=$accountNumber and pl<0 and (type=0 or type=1) and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET win_ratio = case when nof_processed=0 then 0 else nof_won/(nof_processed)*100 end where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET loss_ratio = case when nof_processed=0 then 0 else nof_lost/(nof_processed)*100 end where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_days=(SELECT CASE WHEN FLOOR(DATEDIFF(max(time_close), min(time_open))) = 0 THEN 1 ELSE FLOOR(DATEDIFF(max(time_close), min(time_open))) END  FROM `account_orders` WHERE (type=0 or type=1) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET daily_perc = gain_perc/(total_days) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_months=(SELECT
        TIMESTAMPDIFF(MONTH, min(time_open), max(time_close)) +
        DATEDIFF(
          max(time_close),
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(MONTH, min(time_open), max(time_close))
          MONTH
        ) /
        DATEDIFF(
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(MONTH, min(time_open), max(time_close)) + 1
          MONTH,
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(MONTH, min(time_open), max(time_close))
          MONTH
        ) FROM `account_orders` WHERE (type=0 or type=1) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET monthly_perc=(case when total_months=0 then 0 else gain_perc/(total_months) end) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_weeks=(SELECT
        TIMESTAMPDIFF(WEEK, min(time_open), max(time_close)) +
        DATEDIFF(
          max(time_close),
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(WEEK, min(time_open), max(time_close))
            WEEK
        ) /
        DATEDIFF(
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(WEEK, min(time_open), max(time_close)) + 1
            WEEK,
          min(time_open) + INTERVAL
            TIMESTAMPDIFF(WEEK, min(time_open), max(time_close))
            WEEK
        ) FROM `account_orders` WHERE (type=0 or type=1) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl SET weekly_perc=(case when total_weeks=0 then 0 else gain_perc/(total_weeks) end) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_swap=(SELECT IFNULL(sum(swap),0) FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set withdrawal=(SELECT sum(pl) FROM `account_orders` WHERE type=6 and pl < 0 and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_profit_pips=(SELECT sum(pips) FROM `account_orders` WHERE (type=1 or type=0)  and status=3 and $key=$accountNumber)  where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_win_pips=(SELECT avg(pips) FROM `account_orders` WHERE (type=0 or type=1) and pl>=0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_win=(SELECT avg(pl) FROM `account_orders` WHERE (type=0 or type=1) and pl>=0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_loss=(SELECT avg(pl) FROM `account_orders` WHERE (type=0 or type=1) and pl<0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_loss_pips=(SELECT avg(pips) FROM `account_orders` WHERE (type=0 or type=1) and pl<0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_lots=(SELECT sum(lots) FROM `account_orders` WHERE (type=1 or type=0) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_longs=(SELECT count(ticket) FROM `account_orders` WHERE (type=0) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set longs_won=(SELECT count(ticket) FROM `account_orders` WHERE (type=0) and pl>=0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set total_shorts=(SELECT count(ticket) FROM `account_orders` WHERE (type=1) and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set shorts_won=(SELECT count(ticket) FROM `account_orders` WHERE (type=1) and pl>=0 and status=3 and $key=$accountNumber) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set best_trade_dol=(SELECT max(pl) FROM `account_orders` WHERE type not in (6,7) and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set worst_trade_dol=(SELECT min(pl) FROM `account_orders` WHERE type not in (6,7) and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set best_trade_pips=(SELECT max(pips) FROM `account_orders` WHERE type not in (6,7) and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set worst_trade_pips=(SELECT min(pips) FROM `account_orders` WHERE type not in (6,7) and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set first_trade_day=(SELECT min(time_open) FROM `account_orders` WHERE type in (0,1) and $key=$accountNumber and status=3) where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_trades_per_day=nof_processed/total_days where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set avg_profit_per_day=total_profit/total_days where $key=$accountNumber";
        DB::statement($q);

        $q = "update $tbl set current_pace=daily_perc*365 where $key=$accountNumber";
        DB::statement($q);

        return $this;
    }

    /**
     * @param $accountNumber
     * @return \stdClass
     */
    public static function calcStat($accountNumber)
    {
        $orders = Order::whereAccountNumber($accountNumber)->marketClosed()->orderBy('time_close', 'ASC')
            ->get(['pl', 'pips', 'time_close', 'time_open', 'commission']);

        $info = new \stdClass();
        $total_pips = 0;
        $total_pl = 0;
        $total_time = 0;
        $total_sec = 0;
        $total_min = 0;

        $wins = 0;
        $win_pl = 0;

        $losses = 0;
        $loss_pl = 0;
        foreach ($orders as $order) {
            $pl = $order->pl + $order->commission;
            $total_pl += $pl;
            $total_pips += $order->pips;

            $open = strtotime($order->time_open);
            $close = strtotime($order->time_close);

            $diff = $close - $open;
            $total_time += $diff;

            if ($pl > 0) {
                ++$wins;
                $win_pl += $pl;
            }

            if ($pl < 0) {
                ++$losses;
                $loss_pl += $pl;
            }
        }

        $info->count = count($orders);
        $info->win_loss = 0;
        $info->win_ratio = 0;

        if ($losses != 0) {
            $avg_loss_pl = round($loss_pl/$losses);
        } else {
            $avg_loss_pl = 0;
        }

        if ($wins != 0) {
            $avg_win_pl = round($win_pl/$wins);
        } else {
            $avg_win_pl = 0;
        }

        if ($info->count != 0) {
            $info->win_ratio = number_format($wins/$info->count*100, 0, '.', '');
        }

        if ($avg_loss_pl != 0) {
            $info->win_loss = abs(number_format($avg_win_pl/$avg_loss_pl, 2, '.', ''));
        }

        $info->total_pips = round($total_pips, 2);
        $info->total_pl = round($total_pl);

        if ($total_time != 0) {
            $total_hour = floor($total_time/60/60);
            $total_min = floor($total_time - $total_hour*60*60)/60;
            $total_sec = $total_time - $total_min*60 - $total_hour*60*60;

            $info->total_time = sprintf('%02d:%02d', $total_hour, $total_min);
        } else {
            $info->total_time = 0;
        }

        if ($info->count != 0) {
            $info->avg_pips = round($total_pips/$info->count);
        } else {
            $info->avg_pips = 0;
        }

        return $info;
    }

    public static function calcWeekly($accountNumber)
    {
        $orders = Order::whereAccountNumber($accountNumber)->marketClosed()
            ->select(
                DB::raw(
                    'date(DATE_ADD( time_open, INTERVAL(-WEEKDAY(time_open)) DAY)) monday, '.
                    ' pl, pips, time_close, commission'
                )
            )
            ->orderBy('time_close', 'ASC')
            ->get();

        $mondays = array();
        foreach ($orders as $order) {
            /** @var mixed $order */
            $pl = $order->pl + $order->commission;

            if (isset($mondays[$order->monday])) {
                $mondays[$order->monday]['trades'] += 1;
                $mondays[$order->monday]['pips'] += $order->pips;
                $mondays[$order->monday]['pl'] += $pl;

                if ($order->pl > 0) {
                    $mondays[$order->monday]['wins'] += 1;
                    $mondays[$order->monday]['win_pl'] += $pl;
                }

                if ($order->pl < 0) {
                    $mondays[$order->monday]['losses'] += 1;
                    $mondays[$order->monday]['loss_pl'] += $pl;
                }

                if ($order->pl == 0) {
                    $mondays[$order->monday]['be'] += 1;
                }
            } else {
                if ($order->pl > 0) {
                    $mondays[$order->monday]['wins'] = 1;
                    $mondays[$order->monday]['losses'] = 0;
                    $mondays[$order->monday]['win_pl'] = $pl;
                    $mondays[$order->monday]['loss_pl'] = 0;
                }
                if ($order->pl < 0) {
                    $mondays[$order->monday]['losses'] = 1;
                    $mondays[$order->monday]['wins'] = 0;
                    $mondays[$order->monday]['win_pl'] = 0;
                    $mondays[$order->monday]['loss_pl'] = $pl;
                }

                if ($order->pl == 0) {
                    $mondays[$order->monday]['be'] = 1;
                } else {
                    $mondays[$order->monday]['be'] = 0;
                }

                $mondays[$order->monday]['pl'] = $order->pl + $order->commission;
                $mondays[$order->monday]['trades'] = 1;
                $mondays[$order->monday]['pips'] = $order->pips;
            }
        }

        foreach ($mondays as $day => $monday) {
            $mondays[$day]['avg_pips'] = number_format($monday['pips']/$monday['trades'], 2, '.', '');
            $mondays[$day]['win_ratio'] = number_format($monday['wins']/$monday['trades']*100, 0, '.', '');

            if ($mondays[$day]['losses'] != 0) {
                $mondays[$day]['avg_loss_pl'] = round($mondays[$day]['loss_pl']/$mondays[$day]['losses']);
            } else {
                $mondays[$day]['avg_loss_pl'] = 0;
            }

            if ($mondays[$day]['wins'] != 0) {
                $mondays[$day]['avg_win_pl'] = round($mondays[$day]['win_pl']/$mondays[$day]['wins']);
            } else {
                $mondays[$day]['avg_win_pl'] = 0;
            }

            $mondays[$day]['pl'] = round($mondays[$day]['pl']);

            $mondays[$day]['win_pl'] = round($mondays[$day]['win_pl'], 2);
            $mondays[$day]['loss_pl'] = round($mondays[$day]['loss_pl'], 2);

            if ($mondays[$day]['avg_loss_pl'] != 0) {
                $mondays[$day]['win_loss'] = number_format(
                    $mondays[$day]['avg_win_pl']/$mondays[$day]['avg_loss_pl'],
                    2,
                    '.',
                    ''
                );
            } else {
                $mondays[$day]['win_loss'] = 0;
            }
        }

        return $mondays;
    }
}
