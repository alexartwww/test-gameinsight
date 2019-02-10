<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

class AuthValidator extends Validator implements ValidatorInterface
{
    public function isValid(Request $request): bool
    {
        if ($this->config['auth'] != $request->getHeader('X_AUTHORIZATION')) {
            $this->validationError = new ValidationError('X-Authorization', 'Wrong authorization code');
            return false;
        } else {
            return true;
        }
    }
}
