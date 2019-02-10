<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

/**
 * Class UserValidator
 * @package GameInsight\Gift\Validator
 */
class UserValidator extends Validator implements ValidatorInterface
{
    /**
     * @param Request $request
     * @return bool
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function isValid(Request $request): bool
    {
        if ($request->getParamValue('user_id') == '' || strlen($request->getParamValue('user_id')) > 36) {
            $this->validationError = new ValidationError('user_id', 'user_id must be not empty and length must be less or equal 36 characters');
            return false;
        } else {
            return true;
        }
    }
}
