<?php

namespace App\Validator\Rules;

class UserEmailIsUniqueCompositeValidatorRule extends EmailCompositeValidatorRule
{
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('unique', $config);

    }
}
