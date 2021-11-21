<?php

namespace App\Validator\Rules;

class AbstractCompositeValidatorRule extends AbstractValidatorRule
{

    /**
     * @var RuleInterface[]
     */
    private $rules = [];

    protected function addRule($ruleName, $config = null)
    {
        $this->rules[] = RuleFactory::getRule($ruleName, $config);
    }

    public function validate($data)
    {
        parent::clearErrors();

        foreach ($this->rules as $rule) {
            $rule->validate($data);

            $errors = $rule->getErrors();
            foreach ($errors as $error) {
                $this->addError($error);
            }
        }
    }

}
