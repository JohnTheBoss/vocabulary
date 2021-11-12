<?php

namespace App\Validator;

class InputValidatorBuilder
{
    private $validatorConfig = [];

    public function addInput($inputName, $validatorType, $data)
    {
        $ruleConfig = new \stdClass();
        $ruleConfig->type = $validatorType;
        $ruleConfig->data = $data;

        $this->validatorConfig[$inputName] = $ruleConfig;
    }

    public function getValidator(): Validator
    {
        if (!empty($this->validatorConfig)) {
            return new Validator($this->validatorConfig);
        }
        throw new \Exception('InputValidatorBuilder empty! Must add less one element with addInput() function!');
    }
}
