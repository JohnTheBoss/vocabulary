<?php

namespace App\RequestModel\Dictionary;

use App\Adapter\RequestAdapterInterface;
use App\RequestModel\AbstractRequest;
use App\Validator\InputValidatorBuilder;

class DictionaryRequest extends AbstractRequest
{
    private $validatorBuilder;

    public ?string $name;
    public ?string $knownLanguage;
    public ?string $foreignLanguage;
    public ?bool $shared;

    public function __construct(RequestAdapterInterface $request, InputValidatorBuilder $validatorBuilder)
    {
        $this->validatorBuilder = $validatorBuilder;
        $data = $request->getJsonRequestData();

        $this->name = $this->getData($data, 'name');
        $this->knownLanguage = $this->getData($data, 'knownLanguage');
        $this->foreignLanguage = $this->getData($data, 'foreignLanguage');
        $this->shared = $this->getData($data, 'shared');
    }

    public function getValidator(): \App\Validator\Validator
    {
        $this->validatorBuilder->addInput('name', 'minLength', $this->name, 2);
        $this->validatorBuilder->addInput('knownLanguage', 'minLength', $this->knownLanguage, 1);
        $this->validatorBuilder->addInput('foreignLanguage', 'minLength', $this->foreignLanguage, 1);
        // $this->validatorBuilder->addInput('shared', 'boolean', $this->foreignLanguage);

        return $this->validatorBuilder->getValidator();
    }
}
