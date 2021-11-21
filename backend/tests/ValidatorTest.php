<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function testFactoryTypeDoesntImplemented()
    {
        $this->expectException(\Exception::class);
        \App\Validator\Rules\RuleFactory::getRule(null);
    }

    public function testContainsValidator()
    {
        /**
         * @var $containsValidator \App\Validator\Rules\RuleInterface
         */
        $containsValidator = \App\Validator\Rules\RuleFactory::getRule('contains', '.');
        $containsValidator->validate('alma');

        $this->assertFalse($containsValidator->isValid());
        $this->assertNotEmpty($containsValidator->getErrors());

        $containsValidator->validate('alma.');
        $this->assertTrue($containsValidator->isValid());
        $this->assertEmpty($containsValidator->getErrors());
    }

    public function testPasswordValidator()
    {
        /**
         * @var $passwordValidator \App\Validator\Rules\RuleInterface
         */
        $passwordValidator = \App\Validator\Rules\RuleFactory::getRule('password');
        $passwordValidator->validate('');

        $this->assertFalse($passwordValidator->isValid());
        $this->assertEquals(3, count($passwordValidator->getErrors()));

        $passwordValidator->validate('alma12');
        $this->assertFalse($passwordValidator->isValid());
        $this->assertEquals(
            [
                'A minimum karakter hossz nem éri el a 8 hosszt!',
                'Tartalmaznia kell speciális karaktert!',
            ],
            $passwordValidator->getErrors()
        );

        $passwordValidator->validate('abc.');
        $this->assertFalse($passwordValidator->isValid());
        $this->assertEquals(
            [
                'A minimum karakter hossz nem éri el a 8 hosszt!',
            ],
            $passwordValidator->getErrors()
        );

        $passwordValidator->validate('abc$1248');
        $this->assertTrue($passwordValidator->isValid());
        $this->assertEmpty($passwordValidator->getErrors());

    }

    public function testDataIsSameValidator()
    {
        /**
         * @var $validator \App\Validator\Rules\RuleInterface
         */

        $config['confirmation_field'] = 'validate_confirmation';
        $config['confirmation_data'] = '123';

        $validator = \App\Validator\Rules\RuleFactory::getRule('isSame', $config);
        $validator->validate('');
        $this->assertFalse($validator->isValid());
        $this->assertEquals(
            [
                'Az értéknek nem egyezik a {validate_confirmation} mező értékével!',
            ],
            $validator->getErrors()
        );


        $validator->validate('333');
        $this->assertFalse($validator->isValid());
        $this->assertEquals(
            [
                'Az értéknek nem egyezik a {validate_confirmation} mező értékével!',
            ],
            $validator->getErrors()
        );


        $validator->validate('123');
        $this->assertTrue($validator->isValid());
        $this->assertEmpty($validator->getErrors());
    }
}
