<?php

namespace App\Adapter\AuthenticationAdapter;

interface JWTAdapterInterface
{

    public function encode($createdTime, array $data = [], $expireTimeInSecond = 60): string;

    public function decode();

    public function validate($token);
}
