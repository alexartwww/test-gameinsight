<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

/**
 * Class ValidationError
 * @package GameInsight\Gift\Validator
 */
class ValidationError
{
    /**
     * @var string
     */
    protected $field;
    /**
     * @var string
     */
    protected $message;

    /**
     * ValidationError constructor.
     * @param string $field
     * @param string $message
     */
    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @param string $message
     * @return ValidationError
     */
    public function setMessage(string $message): ValidationError
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $field
     * @return ValidationError
     */
    public function setField(string $field): ValidationError
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }
}
