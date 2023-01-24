<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Social;
use App\Models\User;
use Dcat\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
//    use ActivationTrait;

    public function getSocialRedirect($provider)
    {
        $providerKey = Config::get('services.'.$provider);

        if (empty($providerKey)) {
            return redirect('/');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function getSocialHandle($provider, Request $request)
    {
        $socialUserObject = Socialite::driver($provider)->user();

        $socialUser = null;

        // Check if email is already registered
        $email = $socialUserObject->email;

        if(is_null($email)) {
            $email = $socialUserObject->name;
        }
        $userCheck = User::where('username', '=', $email)->first();

        if (!$email) {
            $email = 'missing'.Str::random(10).'@'.Str::random(10).'.example.org';
        }

        if (empty($userCheck)) {
            $sameSocialId = Social::where('social_id', '=', $socialUserObject->id)
                ->where('provider', '=', $provider)
                ->first();

            if (empty($sameSocialId)) {
                $socialData = new Social();
                $profile = new Profile();

                $fullname = explode(' ', $socialUserObject->name);
                if (count($fullname) == 1) {
                    $fullname[1] = '';
                }
                $username = $socialUserObject->nickname;

                if ($username == null) {
                    foreach ($fullname as $name) {
                        $username .= $name;
                    }
                }

                // Check to make sure username does not already exist in DB before recording
                //$username = $this->checkUserName($username, $email);
                $username = $email;

                $user = User::create([
                    'username'             => $username,
                    'name'                 => $fullname[0].' '.$fullname[1],
                    'email'                => $email,
                    'password'             => bcrypt(Str::random(40)),
                    'api_token'            => Str::random(12),
                    'activated'            => true,
                    'signup_sm_ip' => \Request::getClientIp(),
                    'creator_id' => Admin::domain()->manager_id,
                    'manager_id' => Admin::domain()->manager_id,
                ]);

                $socialData->social_id = $socialUserObject->id;
                $socialData->provider = $provider;
                $user->social()->save($socialData);
                $user->activated = true;

                $user->profile()->save($profile);
                $user->save();

                if ($socialData->provider == 'github') {
                    $user->profile->github_username = $socialUserObject->nickname;
                }

                // Twitter User Object details: https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object
                if ($socialData->provider == 'twitter') {
                    //$user->profile()->twitter_username = $socialUserObject->screen_name;
                    //If the above fails try (The documentation shows screen_name however so Twitters docs may be out of date.):
                    $user->profile()->twitter_username = $socialUserObject->nickname;
                }
                $user->profile->save();

                $socialUser = $user;
            } else {
                $socialUser = $sameSocialId->user;
            }

            Log::info('Socialite user successfully activated. ', [$socialUser]);

            Admin::guard()->login($socialUser);
            admin_toastr(trans('socials.logged_success'));

            return redirect('/');
            // auth('admin')->login($socialUser, true);

            // //admin_toastr(trans('socials.registerSuccess'));
            // return redirect('/');
        }

        $socialUser = $userCheck;

        Log::info('Socialite user successfully activated. ', [$socialUser]);

        Admin::guard()->login($socialUser);
        admin_toastr(trans('socials.logged_success'));

        return redirect('/');

        // auth('admin')->login($socialUser, true);

        // return redirect('/');
    }

}