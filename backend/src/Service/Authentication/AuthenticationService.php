<?php

namespace App\Service\Authentication;

use App\Adapter\AuthenticationAdapter\JWTAdapterInterface;
use App\Adapter\AuthenticationAdapter\PasswordHashAdapterInterface;
use App\Adapter\RepositoryAdapter\UserRepositoryAdapter;
use App\Entity\User;
use App\RequestModel\Auth\LoginRequest;
use App\RequestModel\RequestModelInterface;
use App\ResponseModel\Auth\LoginResponseModel;
use App\ResponseModel\Auth\TokenResponse;
use App\ResponseModel\ResponseModelInterface;
use App\ResponseModel\UserResponse;
use App\Service\AbstractRequestService;

class AuthenticationService extends AbstractRequestService
{
    private const EXPIRE_TIME_IN_SECOND = 86400 * 30;

    protected RequestModelInterface $requestModel;
    private UserRepositoryAdapter $userRepositoryAdapter;
    private PasswordHashAdapterInterface $passwordHashAdapterInterface;
    private JWTAdapterInterface $JWTAdapterInterface;
    protected ResponseModelInterface $responseModel;

    public function __construct(
        LoginRequest                 $loginRequest,
        UserRepositoryAdapter        $userRepositoryAdapter,
        PasswordHashAdapterInterface $passwordHashAdapterInterface,
        JWTAdapterInterface          $JWTAdapterInterface,
        LoginResponseModel           $loginResponseModel
    )
    {
        $this->requestModel = $loginRequest;
        $this->userRepositoryAdapter = $userRepositoryAdapter;
        $this->passwordHashAdapterInterface = $passwordHashAdapterInterface;
        $this->JWTAdapterInterface = $JWTAdapterInterface;
        $this->responseModel = $loginResponseModel;
    }

    public function getResponseModel(): ResponseModelInterface
    {
        if ($this->checkRequestIsValid()) {
            $this->authentication();
        }

        return $this->responseModel;
    }

    private function authentication()
    {
        $user = $this->findUser($this->requestModel->email);

        if (isset($user) && $this->validateUserPassword($user, $this->requestModel->password)) {
            $this->responseModel->setResponseStatus(true);
            $this->responseModel->setResponseStatusCode(200);

            $token = $this->createAuthTokenResponse($user);
            $this->responseModel->setTokenResponse($token);
            $this->responseModel->setUserResponse($this->getUserResponse($user));
        } else {
            $this->responseModel->setResponseStatus(false);
            $this->responseModel->setResponseStatusCode(401);
            $this->responseModel->setResponseErrors(
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
