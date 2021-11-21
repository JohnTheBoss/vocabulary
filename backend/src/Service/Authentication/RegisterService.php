<?php

namespace App\Service\Authentication;

use App\Adapter\AuthenticationAdapter\JWTAdapterInterface;
use App\Adapter\AuthenticationAdapter\PasswordHashAdapterInterface;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\User;
use App\RequestModel\Auth\RegisterRequest;
use App\ResponseModel\Auth\RegisterResponseModel;
use App\ResponseModel\ResponseModelInterface;
use App\Service\AbstractRequestService;

class RegisterService extends AbstractRequestService
{

    private UserRepositoryAdapter $userRepositoryAdapter;
    private PasswordHashAdapterInterface $passwordHashAdapter;

    public function __construct(
        RegisterRequest              $registerRequest,
        UserRepositoryAdapter        $userRepositoryAdapter,
        PasswordHashAdapterInterface $passwordHashAdapter,
        RegisterResponseModel        $registerResponseModel
    )
    {
        $this->requestModel = $registerRequest;
        $this->userRepositoryAdapter = $userRepositoryAdapter;
        $this->passwordHashAdapter = $passwordHashAdapter;
        $this->responseModel = $registerResponseModel;
    }

    private function register()
    {
        $userEntity = $this->userRepositoryAdapter->getEntity();
        /** @var User $user */
        $user = new $userEntity();
        $user->setFullname($this->requestModel->fullname);
        $user->setEmail($this->requestModel->email);
        $user->setPassword($this->passwordHashAdapter->encrypt($this->requestModel->password));
        $user->setRole(0);

        $this->userRepositoryAdapter->save($user);

        $this->responseModel->setResponseStatusCode(200);
        $this->responseModel->setResponseStatus(true);

    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->checkRequestIsValid()) {
            $this->register();
        }

        return $this->responseModel;
    }
}
