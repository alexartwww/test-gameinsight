<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Validator\ValidatorCollection;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Http\Exceptions\BadRequest;
use GameInsight\Gift\Validator\ValidationError;
use GameInsight\Gift\Action\SendGift;

/**
 * Class SendGiftTest
 */
class SendGiftTest extends TestCase
{
    /**
     * @throws BadRequest
     */
    public function testPositive()
    {
        $data = ['friend_id' => 'Jimi-Hendrix', 'gift_id' => 1];
        $params = ['user_id' => 'Slash', 'day_id' => strval(intval(time() / 86400))];
        $request = new Request(['SERVER_PROTOCOL' => 'HTTP 1.1'], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));
        $request->setParams($params);
        $response = new Response();
        $gift = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\GiftInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $gift->method('send')->willReturn(true);
        $gift->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($params['user_id']),
                $this->equalTo($params['day_id']),
                $this->equalTo($data['friend_id']),
                $this->equalTo($data['gift_id'])
            );

        $action = new SendGift($gift, new ValidatorCollection());

        $response = $action->validate($request)->process($request, $response);
        $body = $response->getBody();
        $headers = $response->getHeaders();

        $this->assertEquals(json_encode([
            'status' => 0,
        ], JSON_UNESCAPED_UNICODE), $body);
        $this->assertEquals([
            ['header' => 'HTTP 1.1 201 Created', 'replace' => true, 'http_response_code' => 201],
            ['header' => 'Status: 201 Created', 'replace' => true, 'http_response_code' => 201],
            ['header' => 'Cache-Control: no-cache,no-store,max-age=0,must-revalidate', 'replace' => true, 'http_response_code' => 0],
            ['header' => 'Content-Type: application/json', 'replace' => true, 'http_response_code' => 0],
        ], $headers);
    }

    /**
     * @throws BadRequest
     */
    public function testNegative()
    {
        $data = ['friend_id' => 'Jimi-Hendrix', 'gift_id' => 1];
        $params = ['user_id' => 'Slash', 'day_id' => strval(intval(time() / 86400))];
        $request = new Request(['SERVER_PROTOCOL' => 'HTTP 1.1'], [], [], [], json_encode($data, JSON_UNESCAPED_UNICODE));
        $request->setParams($params);
        $response = new Response();
        $gift = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\GiftInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $gift->method('send')->willReturn(true);
        $validator = $this->getMockBuilder(\GameInsight\Gift\Validator\Interfaces\ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validator->method('isValid')->willReturn(false);
        $validator->method('getValidationError')->willReturn(new ValidationError('user_id', 'user_id must be God'));

        $action = new SendGift($gift, (new ValidatorCollection())->add($validator));

        $this->expectException(BadRequest::class);
        $action->validate($request)->process($request, $response);
    }
}
