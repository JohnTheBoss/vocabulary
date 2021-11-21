<?php

namespace App\RequestModel\Auth;

use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Adapter\RequestAdapter;
use App\RequestModel\RequestModelInterface;
use App\Validator\InputValidatorBuilder;

class RegisterRequest implements RequestModelInterface
{
    public $fullname;
    public $email;
    public $password;
    public $password_confirmation;

    private InputValidatorBuilder $validatorBuilder;
    private UserRepositoryAdapter $userRepositoryAdapter;

    public function __construct(RequestAdapter $request, InputValidatorBuilder $validatorBuilder, UserRepositoryAdapter $userRepositoryAdapter)
    {
        $this->validatorBuilder = $validatorBuilder;
        $this->userRepositoryAdapter = $userRepositoryAdapter;
        $data = $request->getJsonRequestData();

        $this->fullname = $data['fullname'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->password_confirmation = $data['password_confirmation'];
    }

    public function getValidator(): \App\Validator\Validator
    {
        $this->validatorBuilder->addInput('fullname', 'userName', $this->fullname);
        $this->validatorBuilder->addUniqueInput('email', 'userEmailIsUnique', $this->email, $this->userRepositoryAdapter, 'email');
        $this->validatorBuilder->addConfirmedInput('password', 'passwordConfirmation', $this->password, $this->password_confirmation);

        return $this->validatorBuilder->getValidator();
    }
}
