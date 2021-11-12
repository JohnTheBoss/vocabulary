<?php

namespace App\Service\Authentication;

use App\Adapter\AuthenticationAdapter\JWTAdapterInterface;
use App\Adapter\AuthenticationAdapter\PasswordHashAdapterInterface;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\User;
use App\RequestModel\Auth\LoginRequest;
use App\ResponseModel\Auth\LoginResponseModel;
use App\ResponseModel\Auth\TokenResponse;
use App\ResponseModel\ResponseModelInterface;
use App\ResponseModel\UserResponse;

class AuthenticationService
{
    private const EXPIRE_TIME_IN_SECOND = 86400;

    private LoginRequest $loginRequest;
    private UserRepositoryAdapter $userRepositoryAdapter;
    private PasswordHashAdapterInterface $passwordHashAdapterInterface;
    private JWTAdapterInterface $JWTAdapterInterface;
    private LoginResponseModel $loginResponseModel;

    public function __construct(
        LoginRequest                 $loginRequest,
        UserRepositoryAdapter        $userRepositoryAdapter,
        PasswordHashAdapterInterface $passwordHashAdapterInterface,
        JWTAdapterInterface          $JWTAdapterInterface,
        LoginResponseModel           $loginResponseModel
    )
    {
        $this->loginRequest = $loginRequest;
        $this->userRepositoryAdapter = $userRepositoryAdapter;
        $this->passwordHashAdapterInterface = $passwordHashAdapterInterface;
        $this->JWTAdapterInterface = $JWTAdapterInterface;
        $this->loginResponseModel = $loginResponseModel;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->checkRequestIsValid()) {
            $this->authentication();
        }

        return $this->loginResponseModel;
    }

    private function checkRequestIsValid(): bool
    {
        $validator = $this->loginRequest->getValidator();
        $validator->validate();

        if (!$validator->isValid()) {
            $this->loginResponseModel->setResponseStatus(false);
            $this->loginResponseModel->setResponseStatusCode(400);
            $this->loginResponseModel->setResponseErrors(
                [
                    'validator' => $validator->getErrors()
                ]
            );
            return false;
        }

        return true;
    }

    private function authentication()
    {
        $user = $this->findUser($this->loginRequest->email);

        if (isset($user) && $this->validateUserPassword($user, $this->loginRequest->password)) {
            $this->loginResponseModel->setResponseStatus(true);
            $this->loginResponseModel->setResponseStatusCode(200);

            $token = $this->createAuthTokenResponse($user);
            $this->loginResponseModel->setTokenResponse($token);
            $this->loginResponseModel->setUserResponse($this->getUserResponse($user));
        } else {
            $this->loginResponseModel->setResponseStatus(false);
            $this->loginResponseModel->setResponseStatusCode(401);
            $this->loginResponseModel->setResponseErrors(
                [
                    'auth' => [
                        'Hibás bejelentkezési adatok!'
                    ]
                ]
            );
        }
    }

    public function findUser(string $email): ?User
    {
        return $this->userRepositoryAdapter->findOneBy(
            [
                'email' => $email,
            ]
        );
    }

    public function validateUserPassword($user, $password): bool
    {
        return $this->passwordHashAdapterInterface->verify($password, $user->getPassword());
    }

    private function getUserResponse(User $user): UserResponse
    {
        $userResponse = new UserResponse();

        $userResponse->id = $user->getId();
        $userResponse->email = $user->getEmail();
        $userResponse->fullName = $user->getFullName();

        return $userResponse;
    }

    private function createAuthTokenResponse(User $user): TokenResponse
    {
        $tokenIssueTime = time();
        $tokenExpireTime = $tokenIssueTime + self::EXPIRE_TIME_IN_SECOND;

        $tokenData = new TokenResponse();
        $tokenData->token = $this->JWTAdapterInterface->encode(
            $tokenIssueTime,
            (array)$this->getUserResponse($user),
            self::EXPIRE_TIME_IN_SECOND
        );
        $tokenData->expire = $tokenExpireTime;

        return $tokenData;
    }
}
