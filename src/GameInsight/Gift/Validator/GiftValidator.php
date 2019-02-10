<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

/**
 * Class GiftValidator
 * @package GameInsight\Gift\Validator
 */
class GiftValidator extends Validator implements ValidatorInterface
{
    /**
     * @param Request $request
     * @return bool
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function isValid(Request $request): bool
    {
        if (intval($request->getBodyJsonValue('gift_id')) < 0) {
            $this->validationError = new ValidationError('gift_id', 'gift_id must be greter then zero');
            return false;
        } else {
            return true;
        }
    }
}
