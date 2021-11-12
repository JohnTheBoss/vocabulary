<?php

namespace App\Validator\Rules;

class RequireValidatorRule extends AbstractValidatorRule
{

    function validate($data)
    {
        parent::clearErrors();

        if (empty($data)) {
            $this->addError('Kötelező kitőlteni!');
        }

    }
}
