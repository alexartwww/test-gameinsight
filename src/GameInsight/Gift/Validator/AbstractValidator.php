<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;

abstract class AbstractValidator
{
    protected $validationError;

    abstract public function isValid(Request $request): bool;

    abstract public function getValidationError(): ValidationError;
}
