<?php

namespace App\Validator\Rules;

class EmailCompositeValidatorRule extends AbstractCompositeValidatorRule
{
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('required');
        $this->addRule('minLength', 4);
        $this->addRule('contains', '@');
        $this->addRule(
            'contains',
            [
                'regex' => '/(^\w+([._+-]?\w+)*@\w+([._-]?\w+)*(.\w{2,})$)/',
                'errorMessage' => 'Az email cím csak angol abc és számokat tartalmazhat, speciális karakterekből pedig csak a . - _ + fogadható el! Minta: email.cimem+jatek@email-szolgaltato.hu',
            ]
        );

    }
}
