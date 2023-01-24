<?php

namespace App;

use App\Models\OrderEvent;
use Illuminate\Notifications\Notification;

class OrderEventsChannel
{

  /**
   * Send the given notification.
   *
   * @param  mixed  $notifiable
   * @param  \Illuminate\Notifications\Notification  $notification
   * @return bool
   */
  public function send($notifiable, Notification $notification)
  {

      if (method_exists($notification, 'toOrderEvent')) {
        /** @var mixed $notification  */
        OrderEvent::insertOrIgnore($notification->toOrderEvent($notifiable));
        return true;
    }

    return false;
  }
}
