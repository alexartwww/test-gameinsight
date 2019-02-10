<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

class IdValidator extends Validator implements ValidatorInterface
{
    public function isValid(Request $request): bool
    {
        if ($request->getBodyJsonValue('id') <= 0) {
            $this->validationError = new ValidationError('id', 'id must be greter then zero');
            return false;
        } else {
            return true;
        }
    }
}
