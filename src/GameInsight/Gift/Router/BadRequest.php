<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

class BadRequest extends \Exception
{
    protected $errorCollection;

    public function __construct(string $message = "Bad Request", int $code = 400, \Throwable $previous = null, \Iterator $errorCollection = null)
    {
        $this->errorCollection = $errorCollection;
        parent::__construct($message, $code, $previous);
    }

    public function setErrorCollection(\Iterator $errorCollection)
    {
        $this->errorCollection = $errorCollection;
        return $this;
    }

    public function getErrorCollection()
    {
        return $this->errorCollection;
    }
}
