<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \GameInsight\Gift\Http\Response;

class ResponseTest extends TestCase
{
    public function testPositive()
    {
        $headers = [
            ['header' => 'HTTP/1.1 200 OK', 'replace' => true, 'http_response_code' => 200],
            ['header' => 'Status: 200 OK', 'replace' => true, 'http_response_code' => 200],
            ['header' => 'Cache-Control: no-cache,no-store,max-age=0,must-revalidate', 'replace' => true, 'http_response_code' => 200],
            ['header' => 'Content-Type: application/json', 'replace' => true, 'http_response_code' => 200],
        ];
        $body = '{"friend_id":"Jimi-Hendrix","gift_id":12345}';
        $bodyJson = json_decode($body,true);

        $response = new Response();
        $response->setBody($body);
        $response->send();

        $this->expectOutputString($body);

        $response->setBodyJson($bodyJson);
        foreach ($headers as $header) {
            $response->addHeader($header['header'], $header['replace'], $header['http_response_code']);
        }
        $this->assertEquals($headers, $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }
}
