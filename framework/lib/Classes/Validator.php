<?php

namespace Framework\Classes;

class Validator
{
    /**
     * @var mixed The data to be validated.
     */
    protected $data;

    /**
     * @var array The validation rules.
     */
    protected $rules = [];

    /**
     * @var string|null The path to the language files for error messages.
     */
    protected $langPath;

    /**
     * @var array The validation errors.
     */
    protected $errors = [];

    /**
     * The Validator constructor.
     *
     * @param mixed $data The data to be validated.
     * @param array $rules The validation rules.
     * @param string|null $langPath The path to the language files for error messages.
     */
    protected function __construct($data, array $rules, string $langPath = null)
    {
        $this->data = $data;
        $this->rules = $rules;

        if ($langPath) {
            $this->langPath = substr($langPath, -1) != '.' ? "$langPath." : $langPath;
        }
    }

    /**
     * Create a new Validator instance.
     *
     * @param mixed $data The data to be validated.
     * @param array $rules The validation rules.
     * @param string|null $langPath The path to the language files for error messages.
     * @return static The created instance.
     */
    public static function make($data, array $rules, ?string $langPath = null): static
    {
        return new static($data, $rules, $langPath);
    }

    /**
     * Check if the validation fails.
     *
     * @return bool True if the validation fails, false otherwise.
     */
    public function fails(): bool
    {
        return !$this->validate();
    }

    /**
     * Run the validation.
     *
     * @return bool True if the validation passes, false otherwise.
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $rule) {
            $fieldData = $this->getFieldData($field);
            $rules = explode('|', $rule);

            foreach ($rules as $rule) {
                $this->validateRule($field, $fieldData, $rule);
            }
        }

        return empty($this->errors);
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
     * Get the value of the specific field from the data.
     *
     * @param string $field The field name.
     * @return mixed The value of the field if exists, null otherwise.
     */
    protected function getFieldData(string $field): mixed
    {
        $keys = explode('.', $field);
        $fieldData = $this->data;

        foreach ($keys as $key) {
            if (!is_array($fieldData) || !key_exists($key, $fieldData)) {
                return null;
            }

            $fieldData = $fieldData[$key];
        }

        return $fieldData;
    }

    /**
     * Validate the specific rule for the field.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     * @param string $rule The rule to validate.
     */
    protected function validateRule(string $field, $data, string $rule): void
    {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

        $method = 'validate' . ucfirst($ruleName);

        if (method_exists($this, $method)) {
            $this->$method($field, $data, $ruleParams);
        }
    }

    /**
     * Validate the "required" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateRequired(string $field, $data): void
    {
        if (empty($data)) {
            $this->addError($field, Lang::get('framework::validation.required', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "min" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     * @param array $params The rule parameters.
     */
    protected function validateMin(string $field, $data, array $params): void
    {
        $minValue = intval($params[0]);
    
        if (is_numeric($data) && $data < $minValue) {
            $this->addError($field, Lang::get('framework::validation.min.numeric', [
                'field' => $this->langPath . $field,
                'minValue' => $minValue,
            ]));
        } elseif (is_string($data) && strlen($data) < $minValue) {
            $this->addError($field, Lang::get('framework::validation.min.string', [
                'field' => $this->langPath . $field,
                'minValue' => $minValue,
            ]));
        }
    }

    /**
     * Validate the "max" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     * @param array $params The rule parameters.
     */
    protected function validateMax(string $field, $data, array $params): void
    {
        $maxValue = intval($params[0]);

        if (is_numeric($data) && $data > $maxValue) {
            $this->addError($field, Lang::get('framework::validation.max.numeric', [
                'field' => $this->langPath . $field,
                'maxValue' => $maxValue,
            ]));
        } elseif (is_string($data) && strlen($data) > $maxValue) {
            $this->addError($field, Lang::get('framework::validation.max.string', [
                'field' => $this->langPath . $field,
                'maxValue' => $maxValue,
            ]));
        }
    }

    /**
     * Validate the "between" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     * @param array $params The rule parameters.
     */
    protected function validateBetween(string $field, $data, array $params): void
    {
        $minValue = intval($params[0]);
        $maxValue = intval($params[1]);

        if (is_numeric($data) && ($data < $minValue || $data > $maxValue)) {
            $this->addError($field, Lang::get('framework::validation.between.numeric', [
                'field' => $this->langPath . $field,
                'minValue' => $minValue,
                'maxValue' => $maxValue,
            ]));
        } elseif (is_string($data) && (mb_strlen($data) < $minValue || mb_strlen($data) > $maxValue)) {
            $this->addError($field, Lang::get('framework::validation.between.string', [
                'field' => $this->langPath . $field,
                'minValue' => $minValue,
                'maxValue' => $maxValue,
            ]));
        }
    }

    /**
     * Validate the "string" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateString(string $field, $data): void
    {
        if (!is_string($data)) {
            $this->addError($field, Lang::get('framework::validation.string', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "email" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateEmail(string $field, $data): void
    {
        if (!empty($data) && !filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, Lang::get('framework::validation.email', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "url" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateUrl(string $field, $data): void
    {
        if (!empty($data) && !filter_var($data, FILTER_VALIDATE_URL)) {
            $this->addError($field, Lang::get('framework::validation.url', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "numeric" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateNumeric(string $field, $data): void
    {
        if (!empty($data) && !is_numeric($data)) {
            $this->addError($field, Lang::get('framework::validation.numeric', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "alpha" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateAlpha(string $field, $data): void
    {
        if (!preg_match('/^[a-zA-Z]+$/', $data)) {
            $this->addError($field, Lang::get('framework::validation.alpha', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "alpha_num" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateAlphaNum(string $field, $data): void
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $data)) {
            $this->addError($field, Lang::get('framework::validation.alpha_num', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "alpha_dash" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     */
    protected function validateAlphaDash(string $field, $data): void
    {
        if (!preg_match('/^[a-zA-Z0-9-_]+$/', $data)) {
            $this->addError($field, Lang::get('framework::validation.alpha_dash', [
                'field' => $this->langPath . $field,
            ]));
        }
    }

    /**
     * Validate the "unique" rule.
     *
     * @param string $field The field name.
     * @param mixed $data The field data.
     * @param array $params The rule parameters.
     */
    protected function validateUnique(string $field, $data, array $params): void
    {
        $dataset = $params[0];
        $key = $params[1] ?? $field;
        $ignoredId = $params[2] ?? null;

        if (class_exists($dataset)) {
            $items = $dataset::getAll();
        } else {
            $items = Model::getAll($dataset);
        }

        foreach ($items as $item) {
            if ($item->$key == $data && $item->id != $ignoredId) {
                $this->addError($field, Lang::get('framework::validation.unique', [
                    'field' => $this->langPath . $field,
                ]));
                break;
            }
        }
    }

    /**
     * Add a validation error.
     *
     * @param string $field The field name.
     * @param string $message The error message.
     */
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}