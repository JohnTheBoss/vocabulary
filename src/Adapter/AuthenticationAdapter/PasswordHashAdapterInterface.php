<?php

namespace App\Adapter\AuthenticationAdapter;

interface PasswordHashAdapterInterface
{
    public function encrypt($password): string;

    public function verify($rawPassword, $hash): bool;
}
