<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MT4TerminalApi {

    public static function ParseSrv(string $srv, string $name) {

        $api_url = config('funded.url_terminal_api');
        $url = 'http://'.$api_url.'/terminal/srv-parse';

        $response = Http::attach(
            'attachment', $srv, $name.'.srv'
        )->post($url);

        if ($response->ok()) {
            return $response->body();
        }

        return false;
    }

    public static function CreateAccount(string $brokerHost, int $brokerPort, $name, $email,
            $balance, $leverage, $accountType,
            $companyName, $country = 'Country', $city = 'City', $state= 'State', $zip = 'Zip',
            $address = 'Address', $phone = 'Phone') {

        try {
            $api_url = config('funded.url_terminal_api');
            $url = $url = 'http://'.$api_url.'/terminal/account-create';

            $data['Host'] = $brokerHost;
            $data['Port'] = $brokerPort;
            $data['Name'] = $name;
            $data['Email'] = $email;
            $data['Balance'] = $balance;
            $data['leverage'] = $leverage;
            $data['AccountType'] = $accountType;
            $data['TerminalCompany'] = $companyName;
            $data['Country'] = $country;
            $data['City'] = $city;
            $data['State'] = $state;
            $data['Zip'] = $zip;
            $data['Address'] = $address;
            $data['Phone'] = $phone;

            $json = json_encode($data);
            $response = Http
                ::withBody($json, 'application/json')
                ->post($url);

            Log::info('calling terimal api', ['json' => $json]);
            //print_r($response);
            //"{"user":1820781989,"password":"2whtijz","investor":"s2ifkzb"}"
            if ($response->ok()) {
                return $response->body();
            } else {
                Log::critical('Faile to call terminal api', ['json'=> $json, 'resp'=> $response->body()]);
                return FALSE;
            }

        } catch(\Exception $e) {
            Log::critical($e->getMessage());
            return FALSE;
        }

        Log::error('error calling terimal api', ['json' => $json]);
        return FALSE;

    }

}
