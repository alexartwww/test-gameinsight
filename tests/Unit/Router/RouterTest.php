<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GameInsight\Gift\Router\Router;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Http\Exceptions\NotFound;

class RouterTest extends TestCase
{
    public function testPositive()
    {
        $request = new Request();
        $response = new Response();
        $action = $this->getMockBuilder(\GameInsight\Gift\Action\Interfaces\ActionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $action->method('validate')->willReturn($action);
        $action->method('process')->willReturn($response);
        $rule = $this->getMockBuilder(\GameInsight\Gift\Router\Interfaces\RuleInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $rule->method('isMatch')->willReturn(true);
        $rule->method('getAction')->willReturn($action);

        $result = (new Router())
            ->addRule($rule)
            ->route($request)
            ->validate($request)
            ->process($request, $response);

        $this->assertEquals($response, $result);
    }

    public function testNegative()
    {
        $request = new Request();
        $response = new Response();
        $action = $this->getMockBuilder(\GameInsight\Gift\Action\Interfaces\ActionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $action->method('validate')->willReturn($action);
        $action->method('process')->willReturn($response);
        $rule = $this->getMockBuilder(\GameInsight\Gift\Router\Interfaces\RuleInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $rule->method('isMatch')->willReturn(false);
        $rule->method('getAction')->willReturn($action);

        $this->expectException(NotFound::class);
        (new Router())
            ->addRule($rule)
                ->route($request)
                    ->validate($request)
                        ->process($request, $response);
    }
}
