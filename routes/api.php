<?php

use App\Http\Controllers\Api\RoleController;
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


	Route::prefix('v1')->group(function ()
	{

		Route::middleware('auth:api')->group(function()
		{

			Route::prefix('roles')->group(function ()
			{
					$controller = RoleController::class;
					Route::post('create', [$controller, 'create']);
			});


			Route::prefix('report')->group(function ()
			{

			});
		});
	});