<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

class Response
{
    protected $headers = [];
    protected $body = '';

    public function setBody(string $body): Response
    {
        $this->body = $body;
        return $this;
    }

    public function setBodyAsArray(array $body): Response
    {
        $this->setBody(json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $this;
    }

    public function addHeader(string $header, bool $replace = true, int $http_response_code = 0): Response
    {
        $this->headers[] = [
            'header' => $header,
            'replace' => $replace,
            'http_response_code' => $http_response_code,
        ];
        return $this;
    }

    public function send()
    {
        foreach ($this->headers as $header) {
            header($header['header'], $header['replace'], $header['http_response_code']);
        }
        echo $this->body;
    }
}
