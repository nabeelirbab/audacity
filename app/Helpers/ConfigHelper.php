<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

final class ConfigHelper {

public static function getConfig($key = null, $value = null)
    {
        $session = session();

        if (! $config = $session->get('admin.config')) {
            $config = config('admin');
        }

        if (is_array($key)) {
            // 保存
            foreach ($key as $k => $v) {
                Arr::set($config, $k, $v);
            }

            $session->put('admin.config', $config);

            return;
        }

        if ($key === null) {
            return $config;
        }

        return Arr::get($config, $key, $value);
    }
}