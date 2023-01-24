<?php

use App\Http\Controllers\Auth\ActivateController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {

  Route::get('signal-analysis/{slug}', 'SignalAnalysisController@index');
  Route::get('metrics/{slug}', 'Performance\PerformanceMetricsController@external');

  // Route::get('routes', function () {
  //   $routeCollection = Route::getRoutes();

  //   echo "<table style='width:100%'>";
  //   echo "<tr>";
  //   echo "<td width='10%'><h4>HTTP Method</h4></td>";
  //   echo "<td width='10%'><h4>Route</h4></td>";
  //   echo "<td width='10%'><h4>Name</h4></td>";
  //   echo "<td width='70%'><h4>Corresponding Action</h4></td>";
  //   echo "</tr>";
  //   foreach ($routeCollection as $value) {
  //       echo "<tr>";
  //       echo "<td>" . $value->methods()[0] . "</td>";
  //       echo "<td>" . $value->uri() . "</td>";
  //       echo "<td>" . $value->getName() . "</td>";
  //       echo "<td>" . $value->getActionName() . "</td>";
  //       echo "</tr>";
  //   }
  //   echo "</table>";
  // });

//  Route::get('download/{managerId}/{productKeys}', 'Api\LicensingController@prepareArchive');
});

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');
Route::post('/set-new-password', [ResetPasswordController::class, 'storeNewPassword']);

Route::get('/activate/{token}', [ActivateController::class, 'activate'])->name('activate');

//Route::get('ssologin/{token}', 'Api\UserController@ssoLogin');

require __DIR__.'/social.php';