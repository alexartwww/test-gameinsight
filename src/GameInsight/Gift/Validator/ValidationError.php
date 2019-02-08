<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

class ValidationError
{
    protected $field;
    protected $message;

    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    public function setMessage(string $message): ValidationError
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setField(string $field): ValidationError
    {
        $this->field = $field;
        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
