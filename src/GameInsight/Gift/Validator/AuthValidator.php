<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;

class AuthValidator extends AbstractValidator implements ValidatorInterface
{
    public function isValid(Request $request): bool
    {
        return true;
    }

    public function getValidationError(): ValidationError
    {
        return $this->validationError;
    }
}
