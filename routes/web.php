<?php

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
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('user');
});
//Menu nav
Route::group(['prefix' => 'menu-nav'], function () {
    Route::get("/", function(){
        return View::make("layout-menu");
    });
});
Route::get("/uploads")->name("uploads");
#Route::get('/', 'IndexController@indexAction')->name('home');
#Route::match(['get', 'post'], '/login', 'LoginController@indexAction');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
Route::post('/connection-string', function(){
    Artisan::call('epoint:connection_string');
});
