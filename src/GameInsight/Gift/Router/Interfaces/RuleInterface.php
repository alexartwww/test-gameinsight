<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router\Interfaces;

use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Http\Request;

interface RuleInterface
{
    public function isMatch(Request $request): bool;

    public function getMatchParams(Request $request): array;

    public function getAction(Request $request): ActionInterface;
}
