<?php

namespace App\Repositories;

use App\Models\Activation;
use App\Models\User;
use App\Notifications\UserActivationRequiredNotification;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Illuminate\Support\Str;

class ActivationRepository
{

    public function createTokenAndSendEmail(User $user) : bool
    {
        $activations = Activation::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subHours(config('registration.time_period')))
            ->count();

        if ($activations >= config('registration.max_attempts')) {
            return true;
        }

        //if user changed activated email to new one
        if ($user->activated) {
            $user->update([
                'activated' => false,
            ]);
        }

        // Create new Activation record for this user
        $activation = $this->createNewActivationToken($user);

        // Send activation email notification
        $this->sendNewActivationEmail($user, $activation->token);

        return true;
    }

    public function createNewActivationToken(User $user) : Activation
    {
        $activation = new Activation();
        $activation->user_id = $user->id;
        $activation->token = Str::random(64);
        $activation->ip_address = \Request::getClientIp();
        $activation->save();

        return $activation;
    }

    public function sendNewActivationEmail(User $user, $token) : void
    {
        $url = Admin::domain()->full_url;
        $link =  $url.'/activate?token='.$token;

        $user->notify(new UserActivationRequiredNotification($link, $user->manager_id));
    }

    public function deleteExpiredActivations() : void
    {
        Activation::where('created_at', '<=', Carbon::now()->subHours(72))->delete();
    }
}
