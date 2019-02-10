<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\IdValidator;

class IdValidatorTest extends TestCase
{
    public function testValidatorPositive()
    {
        $data = ['id' => 12345];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new IdValidator();
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    public function testValidatorNegative()
    {
        $data = ['id' => -12345];
        $request = new Request([], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));

        $validator = new IdValidator();
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('id', $validationError->getField());
        $this->assertEquals('id must be greter then zero', $validationError->getMessage());
    }
}
