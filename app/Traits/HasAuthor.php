<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait HasAuthor
{
    
	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
	
	public function updator()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}
	
	public function deletor()
	{
		return $this->belongsTo(User::class, 'deleted_by');
	}
	
	public function getCreatorNameAttribute()
	{
		return Str::title($this->creator?->first_name." ". $this->creator?->last_name);
	}

	public function getUpdatorNameAttribute()
	{
		return Str::title($this->updator?->first_name." ". $this->updator?->last_name);
	}

	public function getDeletorNameAttribute()
	{
		return Str::title($this->deletor?->first_name." ". $this->deletor?->last_name);
	}
}