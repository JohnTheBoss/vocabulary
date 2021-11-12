<?php

namespace App\RequestModel\Auth;

use App\Adapter\RequestAdapter;

class Login
{
    public $email;
    public $password;

    public function __construct(RequestAdapter $request)
    {
        $data = $request->getJsonRequestData();

        $this->email = $data['email'];
        $this->password = $data['password'];
    }

}
