<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router\Interfaces;

use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Http\Request;

/**
 * Interface RuleInterface
 * @package GameInsight\Gift\Router\Interfaces
 */
interface RuleInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function isMatch(Request $request): bool;

    /**
     * @param Request $request
     * @return array
     */
    public function getMatchParams(Request $request): array;

    /**
     * @param Request $request
     * @return ActionInterface
     */
    public function getAction(Request $request): ActionInterface;
}
