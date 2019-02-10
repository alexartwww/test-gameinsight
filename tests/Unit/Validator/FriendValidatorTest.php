<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\FriendValidator;

/**
 * Class FriendValidatorTest
 */
class FriendValidatorTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorPositive()
    {
        $data = ['friend_id' => 'Jimi-Hendrix'];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new FriendValidator();
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorNegative()
    {
        $data = ['friend_id' => ''];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new FriendValidator();
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('friend_id', $validationError->getField());
        $this->assertEquals('friend_id must be not empty and length must be less or equal 36 characters', $validationError->getMessage());
    }
}
