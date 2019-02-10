<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\GiftValidator;

/**
 * Class GiftValidatorTest
 */
class GiftValidatorTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorPositive()
    {
        $data = ['gift_id' => 12345];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new GiftValidator();
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorNegative()
    {
        $data = ['gift_id' => -12345];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new GiftValidator();
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('gift_id', $validationError->getField());
        $this->assertEquals('gift_id must be greter then zero', $validationError->getMessage());
    }
}
