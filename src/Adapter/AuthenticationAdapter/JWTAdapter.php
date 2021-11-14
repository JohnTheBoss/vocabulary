<?php

namespace App\Adapter\AuthenticationAdapter;

use Firebase\JWT\JWT;

class JWTAdapter implements JWTAdapterInterface
{
    private string $jwtSecret;
    private string $jwtIssuer;

    public function __construct(string $jwtSecret, string $jwtIssuer)
    {
        $this->jwtSecret = $jwtSecret;
        $this->jwtIssuer = $jwtIssuer;
    }

    public function encode($createdTime, array $data = [], $expireTimeInSecond = 60): string
    {
        $token = $this->getBaseToken();
        $token['iat'] = $createdTime;
        $token['exp'] = $createdTime + $expireTimeInSecond;
        $token['data'] = $data;

        return JWT::encode($token, $this->jwtSecret);
    }

    public function decode()
    {

    }

    public function validate($token): object
    {
        return JWT::decode($token, $this->jwtSecret, ['HS256']);
    }

    private function getBaseToken()
    {
        return [
            'iss' => $this->jwtIssuer,
            'aud' => $this->jwtIssuer,
            'iat' => 0,
            'exp' => 0,
            'data' => [],
        ];
    }
}
