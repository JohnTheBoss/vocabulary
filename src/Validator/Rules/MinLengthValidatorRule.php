<?php

namespace App\Validator\Rules;

class MinLengthValidatorRule extends AbstractValidatorRule
{

    public function __construct($config = 2)
    {
        parent::__construct($config);
    }

    function validate($data)
    {
        parent::clearErrors();

        if (strlen($data) < $this->config) {
            $this->addError('A minimum karakter hossz nem éri el a ' . $this->config . ' hosszt!');
        }
    }
}
