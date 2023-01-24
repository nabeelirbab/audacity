<?php

namespace App;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class UserManagerNotifier
{
  public static function handle(int $managerId, string $message, string $type, string $id)
  {
    DatabaseNotification::create([
      'id' => Str::uuid(),
      'notifiable_type' => 'App\Models\User',
      'notifiable_id' => $managerId,
      'data' => ['message' => $message, 'base_notification_id' => $id],
      'type' => $type
    ]);
  }

  /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
  public function send ($notifiable, Notification $notification) {

    if (method_exists($notifiable, 'routeNotificationFor')) {
        $managerId = $notifiable->routeNotificationFor('UserManagerNotifier', $notification);

        if(method_exists($notification, 'toUserManagerNotifier')) {
          $message = $notification->toUserManagerNotifier($notifiable);
          self::handle($managerId, $message, get_class($notification), $notification->id);
        }
    }

  }
}