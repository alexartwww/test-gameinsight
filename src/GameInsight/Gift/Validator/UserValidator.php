<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

class UserValidator extends Validator implements ValidatorInterface
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
