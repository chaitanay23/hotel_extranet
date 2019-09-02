<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Tested Api
// Route::post('webservice/Axis-Room','Axisroomapi@get_request');
Route::post('webservice/Axis-Room/{method}','Axisroomapi@get_request');
//Channel manager integrations
//Web Service Url 

//Rate Gain Webservice Route
Route::post('webservice/rate_gain', 'magicspreeapi@get_request');


//Rez Next Webservice Route
Route::post('webservice/rez_next', 'Msapireznext@get_request');
//End Web Service

//Rez Next Webservice Route
//Route::post('webservice/Axis-Room/{method}','Axisroomapi@get_request');
//Route::get('webservice/Axis-Room/{method}', 'Axisroomapi@get_request');
//End Web Service

//Rez Next Webservice Route
// Route::post('webservice/Staah-Rooms-ARI', 'Staahrooms@get_request');
Route::post('webservice/Staah-Rooms-ARI/{method}', 'Staahrooms@get_request');
//End Web Service
