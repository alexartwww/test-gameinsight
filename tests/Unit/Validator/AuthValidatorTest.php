<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\AuthValidator;

/**
 * Class AuthValidatorTest
 */
class AuthValidatorTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorPositive()
    {
        $auth = 'code';
        $server = ['HTTP_X_AUTHORIZATION' => $auth];
        $request = new Request($server, [], [], [], '');

        $validator = new AuthValidator(['auth' => $auth]);
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorNegative()
    {
        $auth = 'code';
        $server = ['HTTP_X_AUTHORIZATION' => $auth . '2'];
        $request = new Request($server, [], [], [], '');

        $validator = new AuthValidator(['auth' => $auth]);
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('X-Authorization', $validationError->getField());
        $this->assertEquals('Wrong authorization code', $validationError->getMessage());
    }
}
