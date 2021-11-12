<?php

namespace App\Validator\Rules;

interface RuleInterface
{
    function getErrors(): array;

    function validate($data);

    function isValid(): bool;
}
