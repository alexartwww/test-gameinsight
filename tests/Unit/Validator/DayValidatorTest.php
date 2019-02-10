<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\DayValidator;

/**
 * Class DayValidatorTest
 */
class DayValidatorTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorPositive()
    {
        $params = ['day_id' => '123'];
        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        $validator = new DayValidator();
        $result = $validator->isValid($request);

        $this->assertTrue($result);
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testValidatorNegative()
    {
        $params = ['day_id' => '20001'];
        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        $validator = new DayValidator();
        $result = $validator->isValid($request);

        $this->assertFalse($result);
        $validationError = $validator->getValidationError();
        $this->assertEquals('day_id', $validationError->getField());
        $this->assertEquals('day_id must be greater then 0 and lesser then 20000', $validationError->getMessage());
    }
}
