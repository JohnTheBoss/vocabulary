<?php

namespace App\RequestModel;

use App\Validator\Validator;

interface RequestModelInterface
{
    public function getValidator(): Validator;
}
