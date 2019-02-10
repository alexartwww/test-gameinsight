<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Validator\ValidatorCollection;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Http\Exceptions\BadRequest;
use GameInsight\Gift\Validator\ValidationError;
use GameInsight\Gift\Action\GetGifts;

/**
 * Class GetGiftsTest
 */
class GetGiftsTest extends TestCase
{
    /**
     * @throws BadRequest
     */
    public function testPositive()
    {
        $data = [['id' => 1, 'friend_id' => 'Jimi-Hendrix', 'gift_id' => 1]];
        $params = ['user_id' => 'Slash'];
        $request = new Request(['SERVER_PROTOCOL' => 'HTTP 1.1']);
        $request->setParams($params);
        $response = new Response();
        $gift = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\GiftInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $gift->method('view')->willReturn($data);
        $gift->expects($this->once())
            ->method('view')
            ->with($this->equalTo($params['user_id']));
        $action = new GetGifts($gift, new ValidatorCollection());

        $response = $action->validate($request)->process($request, $response);
        $body = $response->getBody();
        $headers = $response->getHeaders();

        $this->assertEquals(json_encode([
            'status' => 0,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE), $body);
        $this->assertEquals([
            ['header' => 'HTTP 1.1 200 OK', 'replace' => true, 'http_response_code' => 200],
            ['header' => 'Status: 200 OK', 'replace' => true, 'http_response_code' => 200],
            ['header' => 'Cache-Control: no-cache,no-store,max-age=0,must-revalidate', 'replace' => true, 'http_response_code' => 0],
            ['header' => 'Content-Type: application/json', 'replace' => true, 'http_response_code' => 0],
        ], $headers);
    }

    /**
     * @throws BadRequest
     */
    public function testNegative()
    {
        $data = [['id' => 1, 'friend_id' => 'Jimi-Hendrix', 'gift_id' => 1]];
        $params = ['user_id' => 'Slash'];
        $request = new Request(['SERVER_PROTOCOL' => 'HTTP 1.1']);
        $request->setParams($params);
        $response = new Response();
        $gift = $this->getMockBuilder(\GameInsight\Gift\Domain\Interfaces\GiftInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $gift->method('view')->willReturn($data);
        $validator = $this->getMockBuilder(\GameInsight\Gift\Validator\Interfaces\ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validator->method('isValid')->willReturn(false);
        $validator->method('getValidationError')->willReturn(new ValidationError('user_id', 'user_id must be God'));
        $action = new GetGifts($gift, (new ValidatorCollection())->add($validator));

        $this->expectException(BadRequest::class);
        $action->validate($request)->process($request, $response);
    }
}
