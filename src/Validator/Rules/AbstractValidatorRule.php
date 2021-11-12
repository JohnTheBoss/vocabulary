<?php

namespace App\Validator\Rules;

abstract class AbstractValidatorRule implements RuleInterface
{
    protected $config = null;

    private $errors = [];

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    protected function addError($error)
    {
        $this->errors[] = $error;
    }

    protected function clearErrors()
    {
        $this->errors = [];
    }
}
