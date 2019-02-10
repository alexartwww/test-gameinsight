<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Router\Rule;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Http\Request;

/**
 * Class RuleTest
 */
class RuleTest extends TestCase
{
    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testPositive()
    {
        $method = 'GET';
        $uriRegExp = '#^/gifts/(?P<user_id>[0-9a-zA-Z\-]+)$#';
        $request = new Request(['REQUEST_METHOD' => 'GET'], [], ['path' => '/gifts/Brian-May'], [], '');
        $response = (new Response())
            ->setBodyJson([
                'status' => 0,
                'data' => [
                    'id' => 3,
                    'friend_id' => 'Jimi-Page',
                    'gift_id' => 3,
                ],
            ]);
        $action = $this->getMockBuilder(\GameInsight\Gift\Action\Interfaces\ActionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $action->method('process')->willReturn($response);

        $rule = new Rule($method, $uriRegExp, $action);

        $this->assertTrue($rule->isMatch($request));
        $this->assertEquals(['user_id' => 'Brian-May', 0 => '/gifts/Brian-May', 1 => 'Brian-May'], $rule->getMatchParams($request));
        $this->assertEquals($action, $rule->getAction($request));
        $this->assertEquals($response, $rule->getAction($request)->process($request, $response));
    }

    /**
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function testNegative()
    {
        $method = 'GET';
        $uriRegExp = '#^/gifts/(?P<user_id>[0-9a-zA-Z\-]+)$#';
        $request = new Request(['REQUEST_METHOD' => 'POST'], [], ['path' => '/gifts/Brian-May/123432'], [], '');
        $response = (new Response())
            ->setBodyJson([
                'status' => 0,
                'data' => [
                    'id' => 3,
                    'friend_id' => 'Jimi-Page',
                    'gift_id' => 3,
                ],
            ]);
        $action = $this->getMockBuilder(\GameInsight\Gift\Action\Interfaces\ActionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $action->method('process')->willReturn($response);

        $rule = new Rule($method, $uriRegExp, $action);

        $this->assertFalse($rule->isMatch($request));
        $this->assertEquals([], $rule->getMatchParams($request));
        $this->assertEquals($action, $rule->getAction($request));
        $this->assertEquals($response, $rule->getAction($request)->process($request, $response));
    }
}
