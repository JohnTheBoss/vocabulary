<?php

namespace App\Validator\Rules;

class DataIsSameValidatorRule extends AbstractValidatorRule
{

    function validate($data)
    {
        parent::clearErrors();

        if ($data !== $this->config['confirmation_data']) {
            $this->addError('Az értéknek nem egyezik a {' . $this->config['confirmation_field'] . '} mező értékével!');
        }

    }
}
