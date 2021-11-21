<?php

namespace App\Validator\Rules;

class ContainsValidatorRule extends AbstractValidatorRule
{

    public function validate($data)
    {
        parent::clearErrors();

        $containsInfo = $this->getTypeAndContainsRule();
        $type = $containsInfo['type'];
        $rule = $containsInfo['rule'];

        $contains = $this->checkContains($type, $rule, $data);

        if (!$contains) {
            $this->addError($this->getErrorMessage($rule));
        }
    }

    private function checkContains($type, $rule, $data)
    {
        switch ($type) {
            case 'regex':
                return preg_match($rule, $data);
            case 'string':
            default:
                return str_contains($data, $rule);
        }
    }

    private function getTypeAndContainsRule()
    {
        $type = 'string';
        $rule = $this->config;

        if (is_array($this->config)) {
            if (isset($this->config['regex'])) {
                $type = 'regex';
                $rule = $this->config['regex'];
            }
        }

        return [
            'type' => $type,
            'rule' => $rule,
        ];
    }

    private function getErrorMessage($rule)
    {
        if (is_array($this->config)) {
            if (isset($this->config['errorMessage'])) {
                return $this->config['errorMessage'];
            }
        }

        return 'Nem tartalmazz \'' . $rule . '\' karaktert!';
    }
}
