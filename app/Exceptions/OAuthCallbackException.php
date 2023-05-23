<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class OAuthCallbackException extends Exception
{
	/**
	 * Report the exception
	 */
	public function report()
	{
		return response([
									'errors' => [
										'status' => 403,
										'message' => 'authorization failled'
									]
                ]);
	}

	/**
	 * Report the exception into an HTTP response
	 */
	public function render(Request $request)
	{
		if ($request->is('api/*')) {
			return response()->json([
				'message' => 'authorization failled'
			], 403);
		}

		return response()->view('errors.oauth_failed', ['message' => 'authorization failled'], 403);
	}
}
