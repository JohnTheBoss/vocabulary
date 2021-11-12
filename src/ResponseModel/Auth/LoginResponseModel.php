<?php

namespace App\ResponseModel\Auth;

use App\ResponseModel\AbstractResponseModel;
use App\ResponseModel\UserResponse;

class LoginResponseModel extends AbstractResponseModel
{
    private UserResponse $user;

    private TokenResponse $tokenResponse;

    public function setUserResponse(UserResponse $user)
    {
        $this->user = $user;
    }

    public function setTokenResponse(TokenResponse $tokenResponse)
    {
        $this->tokenResponse = $tokenResponse;
    }

    protected function responseData(): array
    {
        if ($this->statusResponse->success) {
            return [
                'user' => $this->user,
                'token' => $this->tokenResponse,
            ];
        }
        return [];
    }
}

