<?php

namespace App\Validator;

use App\Adapter\RepositoryAdapter\EntityRepositoryInterface;

class InputValidatorBuilder
{
    private $validatorConfig = [];

    public function addInput($inputName, $validatorType, $data, $config = null)
    {
        $ruleConfig = new \stdClass();
        $ruleConfig->type = $validatorType;
        $ruleConfig->data = $data;
        $ruleConfig->config = $config;

        $this->validatorConfig[$inputName] = $ruleConfig;
    }

    public function addUniqueInput($inputName, $validatorType, $data, EntityRepositoryInterface $entityRepository, $field)
    {
        $config['entityRepository'] = $entityRepository;
        $config['entityField'] = $field;
        $this->addInput($inputName, $validatorType, $data, $config);
    }

    public function addConfirmedInput($inputName, $validatorType, $data, $confirmedData, $config = [])
    {
        $config['confirmation_field'] = $inputName;
        $config['confirmation_data'] = $confirmedData;
        $this->addInput($inputName, $validatorType, $data, $config);

        $config['confirmation_field'] = $inputName . '_confirmation';
        $config['confirmation_data'] = $data;
        $this->addInput($inputName . '_confirmation', $validatorType, $confirmedData, $config);
    }

    public function getValidator(): Validator
    {
        if (!empty($this->validatorConfig)) {
            return new Validator($this->validatorConfig);
        }
        throw new \Exception('InputValidatorBuilder empty! Must add less one element with addInput() function!');
    }
}
