<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http\Exceptions;

class NotFound extends \Exception
{
    public function __construct(string $message = "Not Found", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
