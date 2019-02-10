<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\UserValidator;

/**
 * Class UserValidatorTest
 */
class UserValidatorTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorPositive()
    {
        $params = ['user_id' => 'Slash'];
        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        $validator = new UserValidator();
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorNegative()
    {
        $params = ['user_id' => ''];
        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        $validator = new UserValidator();
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('user_id', $validationError->getField());
        $this->assertEquals('user_id must be not empty and length must be less or equal 36 characters', $validationError->getMessage());
    }
}
