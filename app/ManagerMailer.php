<?php

namespace App;

use App\ManagerTemplateMailable;
use App\Models\ManagerMailSetting;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ManagerMailer
{
  public static function handle(ManagerTemplateMailable $mailable, string $to)
  {
    $setting = ManagerMailSetting::find($mailable->getManagerId());

    if($setting) {
        if(!empty($setting->main_template))
            $mailable->setLayout($setting->main_template);

        $mailer = app()->makeWith('custom.mailer', $setting->toArray());

        $mailer->to($to)->send($mailable);
    }
     else {
        Mail::to($to)->send($mailable);
     }
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
        $email = $notifiable->routeNotificationFor('ManagerMailer', $notification);

        if(method_exists($notification, 'toManagerMailer')) {
          /** @var ManagerTemplateMailable $mailable */
          /** @var mixed $notification */
          $mailable = $notification->toManagerMailer($notifiable);

          if ($mailable instanceof ManagerTemplateMailable) {
            ManagerMailer::handle($mailable, $email);
          }
        }
    }
  }
}