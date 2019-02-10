<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;

/**
 * Class Validator
 * @package GameInsight\Gift\Validator
 */
class Validator
{
    /**
     * @var array
     */
    protected $config=[];
    /**
     * @var
     */
    protected $validationError;

    /**
     * Validator constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isValid(Request $request): bool
    {
        return true;
    }

    /**
     * @return ValidationError
     */
    public function getValidationError(): ValidationError
    {
        return $this->validationError;
    }
}
