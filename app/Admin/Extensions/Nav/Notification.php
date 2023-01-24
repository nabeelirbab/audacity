<?php

namespace App\Admin\Extensions\Nav;

use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Tooltip;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class Notification implements Renderable
{

  private $maxLoad;
  private $icon;
  private $tipClass;

  public function __construct($maxLoad, $tip, $icon = 'ficon feather icon-bell')
  {
    $this->maxLoad = $maxLoad;
    $this->icon = $icon;

    $id = Str::random(4);
    $this->tipClass = 'tt-'.$id;

    Tooltip::make('.'.$this->tipClass)
      ->bottom()
      ->title($tip);
  }

  public function render()
  {

    $notifications = DatabaseNotification
      ::groupBy('type')
      ->take($this->maxLoad)
      ->selectRaw("type as mtype, count(id) as cnt, ".
        "( SELECT created_at FROM notifications WHERE `type`= mtype ORDER BY created_at DESC LIMIT 1 ) AS last_date")
      ->unread()
      ->where('notifiable_id', Admin::user()->id)
      ->get();

    $total = 0;
    $data = [];
    foreach( $notifications as $item ) {

      /** @var mixed $item */
      $type = $item->mtype;
      $total += $item->cnt;
      $data[] =  [
        'icon' => $type::getIcon(),
        'title' => Str::remove('App\\Notifications\\', $type),
        'count' => $item->cnt,
        'last_date' => Carbon::parse($item->last_date)->diffForHumans()
      ];
    }

    $readAllUrl = Admin::user()->can('user.notifications')
      ? admin_url('my-notifications')
      : admin_url('notifications');

    $vars = [
      'icon'           => $this->icon,
      'total'          => $total,
      'notifications'  => $data,
      'read_all_url'   => $readAllUrl,
      'tip_class'   => $this->tipClass,
    ];

    return view('notifications', $vars);
  }
}