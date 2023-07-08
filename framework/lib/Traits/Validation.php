<?php

namespace Framework\Traits;

use Framework\Classes\Validator;

trait Validation
{
    /**
     * @var array The validation errors.
     */
    protected $errors = [];

    /**
     * Validate the model data.
     *
     * @return bool True if the validation passes, false otherwise.
     */
    public function validate(): bool
    {
        $validator = Validator::make($this->toArray(), $this->updateUniqueRules(), $this->langPath ?? null);

        if ($validator->fails()) {
            $this->errors = $validator->getErrors();
            return false;
        }

        return true;
    }

    /**
     * Get the validation errors.
     *
     * @return array The validation errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get the first validation error message.
     *
     * @return string|null The first validation error message, or null if there are no errors.
     */
    public function getFirstError(): ?string
    {
        if ($this->errors) {
            return current($this->errors)[0];
        }

        return null;
    }


    /**
     * Save the model with validation.
     *
     * @return bool True on success, false on failure.
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return parent::save();
    }

    /**
     * Save the model without validation.
     *
     * @return bool True on success, false on failure.
     */
    public function forceSave(): bool
    {
        return parent::save();
    }

    /**
     * Update the "unique" rules for the model.
     *
     * @return array The updated "unique" rules.
     */
    protected function updateUniqueRules(): array
    {
        $rules = [];

        foreach ($this->rules as $field => $rule) {
            $uniqueRule = 'unique:' . static::class . ',' . $field . ',' . $this->id;
            $fieldRules = explode('|', $rule);
            $fieldRules = str_replace('unique', $uniqueRule, $fieldRules);
            $rule = implode('|', $fieldRules);
            $rules[$field] = $rule;
        }

        return $rules;
    }
}