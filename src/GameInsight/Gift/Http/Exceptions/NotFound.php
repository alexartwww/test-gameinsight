<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http\Exceptions;

/**
 * Class NotFound
 * @package GameInsight\Gift\Http\Exceptions
 */
class NotFound extends \Exception
{
    /**
     * NotFound constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Not Found", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
