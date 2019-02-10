<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

/**
 * Class ValidationErrorCollection
 * @package GameInsight\Gift\Validator
 */
class ValidationErrorCollection implements \Iterator
{
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var array
     */
    private $collection = [];

    /**
     * ValidationErrorCollection constructor.
     */
    public function __construct() {
        $this->position = 0;
    }

    /**
     *
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current() {
        return $this->collection[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key() {
        return $this->position;
    }

    /**
     *
     */
    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->collection[$this->position]);
    }

    /**
     * @param ValidationError $validationError
     * @return $this
     */
    public function add(ValidationError $validationError)
    {
        $this->collection[] = $validationError;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $strings = [];
        foreach ($this->collection as $validationError) {
            $strings[] = $validationError->getField() . ': ' . $validationError->getMessage();
        }
        return implode("\n", $strings);
    }
}
