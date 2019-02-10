<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http\Exceptions;

/**
 * Class BadRequest
 * @package GameInsight\Gift\Http\Exceptions
 */
class BadRequest extends \Exception
{
    /**
     * @var \Iterator
     */
    protected $errorCollection;

    /**
     * BadRequest constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param \Iterator|null $errorCollection
     */
    public function __construct(string $message = "Bad Request", int $code = 400, \Throwable $previous = null, \Iterator $errorCollection = null)
    {
        $this->errorCollection = $errorCollection;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param \Iterator $errorCollection
     * @return $this
     */
    public function setErrorCollection(\Iterator $errorCollection)
    {
        $this->errorCollection = $errorCollection;
        return $this;
    }

    /**
     * @return \Iterator|null
     */
    public function getErrorCollection()
    {
        return $this->errorCollection;
    }
}
