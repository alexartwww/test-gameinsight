<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \GameInsight\Gift\Config;
use \GameInsight\Gift\Http\Request;

class RequestTest extends TestCase
{
    protected $request;

    public function setUp()
    {

    }

    public function testParams()
    {
        $params = ['user_id' => 'Jimi-Hendrix', ''];

        $request = new Request([], [], [], [], '');
        $request->setParams()
    }
}
