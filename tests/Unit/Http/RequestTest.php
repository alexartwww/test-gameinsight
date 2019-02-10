<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Exceptions\BadRequest;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
{
    /**
     * @throws BadRequest
     */
    public function testParamsPositive()
    {
        $params = ['friend_id' => 'Jimi-Hendrix', 'gift_id' => '12345'];

        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        foreach (array_keys($params) as $key) {
            $this->assertEquals($params[$key], $request->getParamValue($key));
        }
    }

    /**
     * @throws BadRequest
     */
    public function testParamsNegative()
    {
        $params = ['friend_id' => 'Jimi-Hendrix', 'gift_id' => '12345'];
        $unknownParam = 'user_id';

        $request = new Request([], [], [], [], '');
        $request->setParams($params);

        $this->expectException(BadRequest::class);
        $request->getParamValue($unknownParam);
    }

    /**
     * @throws BadRequest
     */
    public function testGetPositive()
    {
        $get = ['path' => '/gifts/Brian-May', 'gift_id' => '12345'];

        $request = new Request([], [], $get, [], '');

        foreach (array_keys($get) as $key) {
            $this->assertEquals($get[$key], $request->getGetValue($key));
        }
        $this->assertEquals($get['path'], $request->getUri());
    }

    /**
     * @throws BadRequest
     */
    public function testGetNegative()
    {
        $get = ['path' => '/gifts/Brian-May', 'gift_id' => '12345'];
        $unknownParam = 'user_id';

        $request = new Request([], [], $get, [], '');

        $this->expectException(BadRequest::class);
        $request->getGetValue($unknownParam);
    }

    /**
     * @throws BadRequest
     */
    public function testServerPositive()
    {
        $server = ['HTTP_X_AUTHORIZATION' => 'supersecret', 'REQUEST_METHOD' => 'POST', 'VERSION' => '1234'];

        $request = new Request($server, [], [], [], '');

        foreach (array_keys($server) as $key) {
            $this->assertEquals($server[$key], $request->getServerValue($key));
        }
        $this->assertEquals($server['HTTP_X_AUTHORIZATION'], $request->getHeader('X_AUTHORIZATION'));
        $this->assertEquals($server['REQUEST_METHOD'], $request->getMethod());
    }

    /**
     * @throws BadRequest
     */
    public function testServerNegative()
    {
        $server = ['HTP_X_AUTHORIZATION' => 'supersecret', 'REQUES_METHOD' => 'POST', 'VERSION' => '1234'];
        $unknownParam = 'user_id';

        $request = new Request($server, [], [], [], '');

        $this->expectException(BadRequest::class);
        $request->getServerValue($unknownParam);
        $this->expectException(BadRequest::class);
        $request->getHeader('X_AUTHORIZATION');
        $this->expectException(BadRequest::class);
        $request->getMethod();
    }

    /**
     * @throws BadRequest
     */
    public function testBodyPositive()
    {
        $body = '{"friend_id": "Jimi-Hendrix", "gift_id": 12345}';
        $bodyJson = json_decode($body, true);

        $request = new Request([], [], [], [], $body);

        foreach (array_keys($bodyJson) as $key) {
            $this->assertEquals($bodyJson[$key], $request->getBodyJsonValue($key));
        }
        $this->assertEquals($body, $request->getBody());
    }

    /**
     * @throws BadRequest
     */
    public function testBodyNegative()
    {
        $body = '{"friend_id": "Jimi-Hendrix", "gift_id": 12345}';
        $unknownParam = 'user_id';

        $request = new Request([], [], [], [], $body);

        $this->expectException(BadRequest::class);
        $request->getBodyJsonValue($unknownParam);

        $body = '"friend_id": "Jimi-Hendrix", "gift_id": 12345}';

        $request = new Request([], [], [], [], $body);

        $this->expectException(BadRequest::class);
        $request->getBodyJsonValue($unknownParam);
    }
}
