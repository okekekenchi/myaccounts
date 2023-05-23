<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EditIfNull implements Rule
{
	protected $table;

	//Query attribute
	protected $attribute;

	//Query Value
	protected $value;
	
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(string $table)
	{
		$this->table = $table;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$data = (array) DB::table($this->table)
											->where($this->attribute, $this->value)
											->first();

		return (!$data || $data[$attribute]) ? false : true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'The :attribute can only be updated if the value is not defined.';
	}

	public function on($value, $attribute='id')
	{		
		$this->value = $value;
		$this->attribute = $attribute;
		return $this;
	}
}
