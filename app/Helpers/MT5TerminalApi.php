<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MT5TerminalApi {

    public static function ParseDat(string $datFile) {

        $api_url = config('funded.url_terminal_api');
        $url = 'http://'.$api_url.'/terminal/dat-parse';

        $response = Http::attach(
            'attachment', $datFile, 'servers.dat'
        )->post($url);

        if ($response->ok()) {
            return $response->body();
        }

        return false;
    }

    public static function CreateAccount($name, $email,
            $balance, $leverage, $accountType,
            $companyName, $country = 'Country', $city = 'City', $state= 'State', $zip = 'Zip',
            $address = 'Address', $phone = 'Phone') {

        try {
            $api_url = config('funded.url_terminal_api');
            $url = $url = 'http://'.$api_url.'/terminal/mt5-account-create';

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

            $response = Http
                ::withBody(json_encode($data), 'application/json')
                ->post($url);

            //print_r($response);
            //"{"login":1820781989,"password":"2whtijz","investor":"s2ifkzb"}"
            if ($response->ok()) {
                return $response->body();
            }

        } catch(\Exception $e) {
            Log::critical($e->getMessage());
            return FALSE;
        }

        return FALSE;

    }

}
