<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login',[App\Http\Controllers\AuthController::class,'login']);
    Route::post('logout', [App\Http\Controllers\AuthController::class,'logout']);
    Route::post('refresh', [App\Http\Controllers\AuthController::class,'refresh']);
    Route::get('me', [App\Http\Controllers\AuthController::class,'me']);
    Route::post('register', [App\Http\Controllers\AuthController::class,'register']);
    Route::post('formatUser', [App\Http\Controllers\AuthController::class,'formatUser']);
    Route::put('edit', [App\Http\Controllers\AuthController::class,'edit']);
    Route::post('upload-image', [App\Http\Controllers\AuthController::class,'uploadImage']);
    Route::get('list-users', [App\Http\Controllers\AuthController::class,'listUser']);
    Route::post('list-users-u', [App\Http\Controllers\AuthController::class,'listUserU']);
    Route::put('activate-user', [App\Http\Controllers\AuthController::class,'activateUser']);
    Route::post('login-social', [App\Http\Controllers\AuthController::class,'loginSocial']);
    Route::post('loginLn', [App\Http\Controllers\AuthController::class,'loginLn']);
    Route::post('updatePassword', [App\Http\Controllers\AuthController::class,'updatePassword']);
    Route::post('send-password', [App\Http\Controllers\AuthController::class,'sendPassword']);
    Route::post('change-password', [App\Http\Controllers\AuthController::class,'changePassword']);
    Route::post('update-history', [App\Http\Controllers\AuthController::class,'updateHistory']);
    Route::post('update-donate', [App\Http\Controllers\AuthController::class,'updateDonate']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'plan'
], function ($router) {
    Route::get('get',[App\Http\Controllers\PlanController::class,'get']);
    Route::post('add',[App\Http\Controllers\PlanController::class,'add']);
    Route::put('edit',[App\Http\Controllers\PlanController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\PlanController::class,'delete']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'entry'
], function ($router) {
    Route::get('get',[App\Http\Controllers\EntryController::class,'get']);
    Route::post('add',[App\Http\Controllers\EntryController::class,'add']);
    Route::put('edit',[App\Http\Controllers\EntryController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\EntryController::class,'delete']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'indicator'
], function ($router) {
    Route::get('get',[App\Http\Controllers\IndicatorController::class,'get']);
    Route::post('add',[App\Http\Controllers\IndicatorController::class,'add']);
    Route::post('edit',[App\Http\Controllers\IndicatorController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\IndicatorController::class,'delete']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'business'
], function ($router) {
    Route::get('get',[App\Http\Controllers\BusinessController::class,'get']);
    Route::post('add',[App\Http\Controllers\BusinessController::class,'add']);
    Route::put('edit',[App\Http\Controllers\BusinessController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\BusinessController::class,'delete']);
    Route::get('get-business',[App\Http\Controllers\BusinessController::class,'getBusiness']);
    Route::get('get-business-type',[App\Http\Controllers\BusinessController::class,'getBusinessType']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'template'
], function ($router) {
    Route::get('get',[App\Http\Controllers\TemplateController::class,'get']);
    Route::post('add',[App\Http\Controllers\TemplateController::class,'add']);
    Route::post('edit',[App\Http\Controllers\TemplateController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\TemplateController::class,'delete']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'licenses'
], function ($router) {
    Route::get('get',[App\Http\Controllers\LicenseDistribucionController::class,'get']);
    Route::get('get-group',[App\Http\Controllers\LicenseDistribucionController::class,'getGroup']);
    Route::post('add',[App\Http\Controllers\LicenseDistribucionController::class,'add']);
    Route::put('edit',[App\Http\Controllers\LicenseDistribucionController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\LicenseDistribucionController::class,'delete']);
    Route::get('get-list',[App\Http\Controllers\LicenseDistribucionController::class,'getListBusiness']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'table'
], function ($router) {
    Route::get('get',[App\Http\Controllers\TableController::class,'get']);
    Route::post('add',[App\Http\Controllers\TableController::class,'add']);
    Route::put('edit',[App\Http\Controllers\TableController::class,'edit']);
    Route::post('delete',[App\Http\Controllers\TableController::class,'delete']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'master'
], function ($router) {
    Route::get('get-licenses',[App\Http\Controllers\LicensesController::class,'infoPlan']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'entry-data'
], function ($router) {
    Route::post('get',[App\Http\Controllers\DataEntryController::class,'getEntryData']);
    Route::post('add',[App\Http\Controllers\DataEntryController::class,'addEntryData']);
    Route::get('get-values',[App\Http\Controllers\DataEntryController::class,'getVelues']);
    Route::post('add-values',[App\Http\Controllers\DataEntryController::class,'addValues']);
    Route::post('run',[App\Http\Controllers\RunProcess::class,'run']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'ratios'
], function ($router) {
    Route::post('get',[App\Http\Controllers\IndicatorController::class,'getRatios']);
    Route::post('add',[App\Http\Controllers\DataEntryController::class,'addEntryData']);
    Route::get('get-values',[App\Http\Controllers\DataEntryController::class,'getVelues']);
    Route::post('add-values',[App\Http\Controllers\DataEntryController::class,'addValues']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'ecommerce'
], function ($router) {
    Route::get('get-planes',[App\Http\Controllers\EcommerceController::class,'getPlanes']);
    Route::post('add',[App\Http\Controllers\OrderControoler::class,'add']);
    Route::get('get',[App\Http\Controllers\OrderControoler::class,'getOrders']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'donate'
], function ($router) {
    Route::get('get',[App\Http\Controllers\DonateController::class,'getDonates']);
    Route::post('mail',[App\Http\Controllers\DonateController::class,'mailSend']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'word'
], function ($router) {
    Route::get('get',[App\Http\Controllers\WordController::class,'downloadWord']);
});



/*Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function ($router) {
    Route::post('login',[App\Http\Controllers\AuthController::class,'login']);
    Route::post('formatUser', [App\Http\Controllers\AuthController::class,'formatUser']);
});*/
