<?php

namespace App\Validator\Rules;

class UserNameCompositeValidatorRule extends AbstractCompositeValidatorRule
{

    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('required');
        $this->addRule('minLength', 2);
        $this->addRule(
            'contains',
            [
                'regex' => '/\w/',
                'errorMessage' => 'Csak betűket tartalmazhat!',
            ]
        );

    }
}
