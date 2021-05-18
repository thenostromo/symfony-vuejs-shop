<?php

namespace App\Tests\Unit\Utils\Validator;

use App\Utils\Validator\UserValidator;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class UserValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = new UserValidator();
    }

    public function testValidateEmail(): void
    {
        $test = '@';

        $this->assertSame($test, $this->validator->validateEmail($test));
    }

    public function testValidateEmailEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The email can not be empty.');
        $this->validator->validateEmail(null);
    }

    public function testValidateEmailInvalid(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The email should look like a real email.');
        $this->validator->validateEmail('invalid');
    }
}
