<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddHeaders
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);
		$response->withHeaders([

			'X-Frame-Options' => 'SAMEORIGIN', //DENY
			/**
			 * Prevents a page from loading when reflected cross-site scripting (XSS)
			 * attack is detected
			 */
			'X-XSS-Protection' => 0,

			/**
			 * Prevents MIME type sniffing, which can transform non-executable MIME types
			 * into executable MIME TYPES
			 */
			'X-Content-Type-Options' => 'nosniff',

			'Server' => 'kenmaxiserver'
		]);

		return $response;
	}
}
