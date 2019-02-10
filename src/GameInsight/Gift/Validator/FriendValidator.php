<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

/**
 * Class FriendValidator
 * @package GameInsight\Gift\Validator
 */
class FriendValidator extends Validator implements ValidatorInterface
{
    /**
     * @param Request $request
     * @return bool
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function isValid(Request $request): bool
    {
        if ($request->getBodyJsonValue('friend_id') == '' || strlen($request->getBodyJsonValue('friend_id')) > 36) {
            $this->validationError = new ValidationError('friend_id', 'friend_id must be not empty and length must be less or equal 36 characters');
            return false;
        } else {
            return true;
        }
    }
}
