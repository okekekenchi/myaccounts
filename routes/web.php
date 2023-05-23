<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


	Route::any('oauth/callback', [LoginController::class, 'handleProviderCallback']);

	Route::middleware('auth:web')->group(function()
	{
		Route::get('oauth/redirect', [LoginController::class, 'redirectToProvider'])
					->name('oauth.redirect');

		/**
		 * Handles every other request that was not explicitly defined
		 */
		Route::get('/{path?}', function() {
			return view('layouts.app', ['user' => auth()->user()]);
		})
		->where([ 'path' => '^(?!api\/)[\/\w\.-]*' ])
		->name('app');
	});