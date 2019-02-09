<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

class ValidatorCollection implements \Iterator
{
    private $position = 0;
    private $collection = [];

    public function __construct() {
        $this->position = 0;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->collection[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->collection[$this->position]);
    }

    public function add(ValidatorInterface $validator)
    {
        $this->collection[] = $validator;
        return $this;
    }
}
