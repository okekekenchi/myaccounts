<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthPersistor extends Model
{
	use HasFactory;

	protected $connection = 'mysql_accounts';

	public $timestamps = false;

	protected $casts = [
		'payload' => 'json',
	];
}
