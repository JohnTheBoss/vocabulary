<?php

namespace App\Validator;

use App\Validator\Rules\RuleFactory;

class Validator
{
    private $errors = [];
    private $validatorConfig = [];

    public function __construct(array $validatorConfig)
    {
        $this->validatorConfig = $validatorConfig;
    }

    function validate()
    {
        $this->errors = [];

        foreach ($this->validatorConfig as $inputName => $ruleConfig) {
            $rule = RuleFactory::getRule($ruleConfig->type, $ruleConfig->config);
            $rule->validate($ruleConfig->data);
            $errors = $rule->getErrors();

            if (!empty($errors)) {
                $this->errors[$inputName] = $errors;
            }
        }
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
