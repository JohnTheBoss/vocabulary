<?php

namespace App\RequestModel\Auth;

use App\Adapter\RequestAdapter;
use App\Validator\InputValidatorBuilder;

class LoginRequest
{
    public $email;
    public $password;

    private $validatorBuilder;

    public function __construct(RequestAdapter $request, InputValidatorBuilder $validatorBuilder)
    {
        $this->validatorBuilder = $validatorBuilder;
        $data = $request->getJsonRequestData();

        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getValidator(): \App\Validator\Validator
    {
        $this->validatorBuilder->addInput('email', 'email', $this->email);
        $this->validatorBuilder->addInput('password', 'password', $this->password);

        return $this->validatorBuilder->getValidator();
    }
}
