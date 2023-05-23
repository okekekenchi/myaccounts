<?php

namespace App\Providers;

use App\Guards\KenmaxiSessionGuard;
use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		/**
		 * Handles web session authentication
		 */
		Auth::extend('kenmaxi-session', function ($app, $name)
		{
			$provider = new EloquentUserProvider( $app['hash'],
																						config('auth.providers.users.model'));

			return new KenmaxiSessionGuard($name, $provider, app()->make('session.store'), request());
		});


		/**
		 * Handles api token authentication
		 */
		Auth::viaRequest('kenmaxi-token', function (Request $request)
		{
			if (!$token = $request->bearerToken()) return null;

			$response = Http::withOptions([ 'verify' => config('app.debug') == false ])
											->withToken($token)
											->acceptJson()
											->post(config('services.kenmaxi.server').'/api/v1/user');
			
			return ($response->successful() && $id = $response->object()?->id) ?
			 				User::find($id): null;
		});
	}
}
