<?php

namespace App\Guards;

use App\Models\User;
use Illuminate\Auth\SessionGuard;

class KenmaxiSessionGuard extends SessionGuard
{
  
  /**
   * Get the currently authenticated user.
   *
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function user()
  {
    if (!$user_id = request()->cookie('KUACCID')) return null;
    
		return User::find($user_id);
  }

}