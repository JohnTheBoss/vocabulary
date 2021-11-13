<?php

namespace App\Validator\Rules;

class PasswordConfirmationCompositeValidatorRule extends PasswordCompositeValidatorRule
{
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('isSame', $config);
    }
}
