<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasClient
{

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeForClient($query)
    {
        return $query->where('client_id', Auth::user()->client_id);
    }
}