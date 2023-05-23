<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LoginController extends Controller
{
	/**
	 * OAuth
	 * Redirects users to the authentication server for authorization
	 */
	public function redirectToProvider(Request $request)
	{
		Auth::user()->persist([
			'state' => $state = Str::random(40),
			'code_verifier' => $code_verifier = Str::random(128)
		]);
		
		$codeChallenge = strtr(rtrim(
			base64_encode(hash('sha256', $code_verifier, true)) , '='), '+/', '-_');

		$query = http_build_query([
							'client_id' => config('services.kenmaxi.client_id'),
							'redirect_uri' => config('services.kenmaxi.redirect'),
							'response_type' => 'code',
							'scope' => '',
							'state' => $state,
							'code_challenge' => $codeChallenge,
							'code_challenge_method' => 'S256',
						]);
		
		return redirect(config('services.kenmaxi.server')."/oauth/authorize?".$query);
	}

	/**
	 * Handles the authorization callback after the authorization code is generated
	 */
	public function handleProviderCallback(Request $request)
	{
		if (Auth::check()) {
			$persistor = $request->user()->persistor()
														->where('ip_address', $request->ip())
														->where('user_agent', $request->userAgent())
														->first();

			$payload = (object) $persistor?->payload;

			abort_if(is_null($payload) || $persistor->expires_at < now(), 410, 'Expired page');

			throw_unless(
				strlen($payload->state) > 0 && $payload->state === $request->state,
				InvalidArgumentException::class
			);

			abort_unless(($request->code || $request->error) &&
											$request->state, 422, 'Unprocessable entity');

			return view('layouts.app', [ 'code_verifier' => $payload->code_verifier]);
		}
	}
}
