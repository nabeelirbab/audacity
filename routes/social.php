<?php

use App\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Auth',
    'prefix' => 'social'], static function() {
		Route::get('/redirect/{provider}', [SocialController::class, 'getSocialRedirect']);
		Route::get('/handle/{provider}', [SocialController::class, 'getSocialHandle']);
});