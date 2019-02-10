<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\Interfaces\ValidatorInterface;

/**
 * Class DayValidator
 * @package GameInsight\Gift\Validator
 */
class DayValidator extends Validator implements ValidatorInterface
{
    /**
     * @param Request $request
     * @return bool
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function isValid(Request $request): bool
    {
        if (intval($request->getParamValue('day_id')) < 0 || intval($request->getParamValue('day_id')) > 20000) {
            $this->validationError = new ValidationError('day_id', 'day_id must be greater then 0 and lesser then 20000');
            return false;
        } else {
            return true;
        }
    }
}
