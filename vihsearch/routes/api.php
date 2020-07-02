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

Route::get('/', function (Request $request) {
    return $request->user();
});

Route::post('/import', 'VihecleController@import');

Route::post('/getSearchData', 'VihecleController@getSearchData');
Route::post('/getFilterData', 'VihecleController@getFilterData');
Route::post('/getItemData',   'VihecleController@getItemData');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

