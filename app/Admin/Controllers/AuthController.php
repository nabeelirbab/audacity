<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
    public function __construct() {
        $this->view = 'auth.'.config('admin.login-layout').'.login';
    }
}
