<?php

namespace App\Validator\Rules;

class PasswordCompositeValidatorRule extends AbstractCompositeValidatorRule
{

    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('required');
        $this->addRule('minLength', 8);
        $this->addRule(
            'contains',
            [
                'regex' => '/\W/',
                'errorMessage' => 'Tartalmaznia kell speciális karaktert!',
            ]
        );

    }
}
