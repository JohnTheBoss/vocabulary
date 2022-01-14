<?php

namespace App\RequestModel\Play;

use App\Adapter\RequestAdapterInterface;
use App\RequestModel\AbstractRequest;
use App\Validator\InputValidatorBuilder;
use App\Validator\Validator;

class PuzzleAnswerRequestModel extends AbstractRequest
{

    private InputValidatorBuilder $validatorBuilder;
    public ?string $answer;

    public function __construct(RequestAdapterInterface $request, InputValidatorBuilder $validatorBuilder)
    {
        $this->validatorBuilder = $validatorBuilder;
        $data = $request->getJsonRequestData();

        $this->answer = $this->getData($data, 'answer');
    }

    public function getValidator(): Validator
    {
        $this->validatorBuilder->addInput('answer', 'minLength', $this->answer, 1);

        return $this->validatorBuilder->getValidator();
    }
}