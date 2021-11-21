<?php

namespace App\Service\Authentication;

use App\Adapter\AuthenticationAdapter\JWTAdapterInterface;
use App\Adapter\RequestAdapterInterface;
use Firebase\JWT\ExpiredException;

class TokenDecoderService
{
    public const TOKEN_VALID = 1;
    public const TOKEN_NOT_EXIST = -1;
    public const TOKEN_EXPIRED = -2;
    public const TOKEN_INVALID = -3;

    private const HEADER_TOKEN_PATTERN = "/Bearer\s+(.*)$/i";

    private string $token;

    private JWTAdapterInterface $JWTAdapter;
    private $tokenData;

    public function __construct(RequestAdapterInterface $requestAdapter, JWTAdapterInterface $JWTAdapter)
    {
        $token = $requestAdapter->getHeaders()['authorization'][0] ?? null;
        $this->JWTAdapter = $JWTAdapter;

        $this->token = $this->parseToken($token);
    }

    private function parseToken($token)
    {
        if (empty($token)) {
            return self::TOKEN_NOT_EXIST;
        }

        if (preg_match(self::HEADER_TOKEN_PATTERN, $token, $matches)) {
            $token = $matches[1];
        }

        return $token;
    }

    public function validateToken(): int
    {
        try {
            $this->tokenData = $this->JWTAdapter->validate($this->token);
            return self::TOKEN_VALID;
        } catch (ExpiredException $expiredException) {
            return self::TOKEN_EXPIRED;
        } catch (\Throwable $e) {
            return self::TOKEN_INVALID;
        }
    }

    public function getTokenData()
    {
        $tokenIsValid = $this->validateToken() === self::TOKEN_VALID;
        if ($tokenIsValid) {
            return $this->tokenData->data;
        }
        return null;
    }
}
