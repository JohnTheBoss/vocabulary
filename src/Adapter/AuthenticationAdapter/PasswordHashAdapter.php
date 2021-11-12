<?php

namespace App\Adapter\AuthenticationAdapter;

class PasswordHashAdapter implements PasswordHashAdapterInterface
{

    public function encrypt($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify($rawPassword, $hash): bool
    {
        return password_verify($rawPassword, $hash);
    }
}
