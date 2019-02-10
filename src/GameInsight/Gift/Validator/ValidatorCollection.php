<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

/**
 * Class ValidatorCollection
 * @package GameInsight\Gift\Validator
 */
class ValidatorCollection implements \Iterator
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
     * ValidatorCollection constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->collection[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     *
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->collection[$this->position]);
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function add(ValidatorInterface $validator)
    {
        $this->collection[] = $validator;
        return $this;
    }
}
