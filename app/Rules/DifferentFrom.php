<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentFrom implements Rule
{
    protected $comparator_attribute;
    protected $comparator_value;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $comparator_attribute)
    {
        $this->comparator_attribute = $comparator_attribute;
        $this->comparator_value = request()[$comparator_attribute] ?? null;
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
        return $value !== $this->comparator_value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be different from '. 
                str_replace('_', ' ', $this->comparator_attribute).'.';
    }
}
