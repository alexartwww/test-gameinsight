<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action\Interfaces;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;

/**
 * Interface ActionInterface
 * @package GameInsight\Gift\Action\Interfaces
 */
interface ActionInterface
{
    /**
     * @param Request $request
     * @return ActionInterface
     */
    public function validate(Request $request): ActionInterface;

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function process(Request $request, Response $response): Response;
}
