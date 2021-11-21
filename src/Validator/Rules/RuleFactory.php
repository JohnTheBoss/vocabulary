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
        'unique' => UniqueValidatorRule::class,
        'isSame' => DataIsSameValidatorRule::class,
        'userName' => UserNameCompositeValidatorRule::class,
        'passwordConfirmation' => PasswordConfirmationCompositeValidatorRule::class,
        'userEmailIsUnique' => UserEmailIsUniqueCompositeValidatorRule::class,
        'dictionaryWordUnique' => DictionaryWordUniqueCompositeValidatorRule::class,
    ];

    public static function getRule($name, $config = null)
    {
        if (isset(self::$ruleMap[$name])) {
            return new self::$ruleMap[$name]($config);
        }
        throw new \Exception('The \'' . $name . '\' validator rule type doesn\'t implemented!');
    }
}
