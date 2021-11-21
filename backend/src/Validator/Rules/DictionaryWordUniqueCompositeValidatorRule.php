<?php

namespace App\Validator\Rules;

class DictionaryWordUniqueCompositeValidatorRule extends AbstractCompositeValidatorRule
{
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->addRule('minLength', 1);
        $this->addRule('unique', $config);

    }
}
