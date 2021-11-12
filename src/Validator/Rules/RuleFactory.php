<?php

namespace App\Validator\Rules;

class RuleFactory
{
    private static $ruleMap = [
        'required' => RequireValidatorRule::class,
        'contains' => ContainsValidatorRule::class,
        'minLength' => MinLengthValidatorRule::class,
        'password' => PasswordCompositeValidatorRule::class,
        'email' => EmailCompositeValidatorRule::class,
    ];

    public static function getRule($name, $config = null)
    {
        if (isset(self::$ruleMap[$name])) {
            return new self::$ruleMap[$name]($config);
        }
        throw new \Exception('The \'' . $name . '\' validator rule type doesn\'t implemented!');
    }
}
