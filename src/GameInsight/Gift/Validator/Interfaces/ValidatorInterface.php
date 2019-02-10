<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator\Interfaces;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\ValidationError;

/**
 * Interface ValidatorInterface
 * @package GameInsight\Gift\Validator\Interfaces
 */
interface ValidatorInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function isValid(Request $request): bool;

    /**
     * @return ValidationError
     */
    public function getValidationError(): ValidationError;
}
