<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

/**
 * Class Response
 * @package GameInsight\Gift\Http
 */
class Response
{
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var string
     */
    protected $body = '';

    /**
     * @param string $body
     * @return Response
     */
    public function setBody(string $body): Response
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return Response
     */
    public function setBodyJson(array $body): Response
    {
        $this->setBody(json_encode($body, JSON_UNESCAPED_UNICODE));
        return $this;
    }

    /**
     * @param string $header
     * @param bool $replace
     * @param int $http_response_code
     * @return Response
     */
    public function addHeader(string $header, bool $replace = true, int $http_response_code = 0): Response
    {
        $this->headers[] = [
            'header' => $header,
            'replace' => $replace,
            'http_response_code' => $http_response_code,
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Response
     */
    public function clearHeaders(): Response
    {
        $this->headers = [];
        return $this;
    }

    /**
     *
     */
    public function send()
    {
        foreach ($this->headers as $header) {
            header($header['header'], $header['replace'], $header['http_response_code']);
        }
        echo $this->body;
    }
}
