<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
	use SoftDeletes, HasFactory, Notifiable, HasAuthor,	HasClient;

	protected $connection = 'mysql_accounts';

	protected $hidden = [ 'password', 'remember_token', 'force_logout', 'otp', 'otp_expires_at' ];
	
	protected $casts = [
		'profile_picture' => 'json',
		'social_media' => 'json',
		'force_logout' => 'boolean',
		'active' => 'boolean',
		'type' => 'string',
	];

	protected $appends = [ 'signature'];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function signature()
	{
		return $this->hasOne(Signature::class);
	}

	public function getSignatureAttribute()
	{
		return Signature::where('user_id', $this->id)->first();
	}

	public function getProfilePictureAttribute($value)
	{
		if (!$value) return null;
		$pic = json_decode($value);

		return config('services.kenmaxi.server').'/storage/images/'.$pic->path;
	}

	public function ip_addresses()
	{
		return $this->hasMany(IpAddress::class);
	}

	public function persistor()
	{
		return $this->hasOne(OauthPersistor::class);
	}

	/**
	 * Laravel session does not always persist
	 * This was developed to persist states during authorization
	 */
	public function persist(array $data)
	{
		if ($persistor = $this->persistor()->first())
		{
			return $persistor->forceFill([
							'payload' => $data,
							'ip_address' => request()->ip(),
							'user_agent' => request()->userAgent(),
							'expires_at' => now()->addMinutes(3)
						])->save();
		}
		
		return $this->persistor()->forceCreate([
						'payload' => $data,
						'ip_address' => request()->ip(),
						'user_agent' => request()->userAgent(),
						'expires_at' => now()->addMinutes(3)
					])->save();
	}
	
	public function team()
	{
		return $this->belongsTo(Team::class);
	}

	public function getNameAttribute()
	{
		if ($this->type === 'business') return $this->business_name;

		return $this->id == Auth::id() ? 'Me' : Str::title($this->first_name.' '.$this->last_name);
	}

	public function getRoleNameAttribute()
	{
		return $this->role->name;
	}    

	public function getTeamNameAttribute()
	{
		return $this->team->name;
	}

	public function markedForLogout()
	{
		return $this->force_logout ? true : false;
	}

	/**
	 * Must be called to generate a fresh token for verification
	 */
	private function generate_otp($is_string = false)
	{
		$otp = $is_string ? Str::random(40) : random_int(200000, 999999);

		$data = [ 'otp' => Hash::make($otp),
							'otp_expires_at' => now()->addMinutes(3) ];

		return $this->forceFill($data)->save() ? $otp : false;
	}

	/**
	 * Scope a query not to include logged in user.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithoutMe($query)
	{
			return $query->where('id', '<>', Auth::id());
	}

	/**
	 * Scope a query to only include users of a given type.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  mixed  $type
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeType($query, $types)
	{
		if ( $types == 'all' ) return;
		
		return gettype($types) === 'string' ? $query->where('type', $types) : $query->whereIn('type', $types);
	}

	public function getFacebookAttribute()
	{
		return $this?->social_media['facebook'] ?? null;
	}

	public function getInstagramAttribute()
	{
		return $this?->social_media['instagram'] ?? null;
	}

	public function getTwitterAttribute()
	{
		return $this?->social_media['twitter'] ?? null;
	}

	public function getLinkedinAttribute()
	{
		return $this?->social_media['linkedin'] ?? null;
	}
	
	// protected static function booted()
	// {
	//     static::addGlobalScope(new UserScope);
	// }

}