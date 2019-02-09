<?php
declare(strict_types=1);

namespace GameInsight\Gift\Validator\Interfaces;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Validator\ValidationError;

interface ValidatorInterface
{
    public function isValid(Request $request): bool;

    public function getValidationError(): ValidationError;
}