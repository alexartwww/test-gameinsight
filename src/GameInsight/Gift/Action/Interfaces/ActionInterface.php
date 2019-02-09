<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action\Interfaces;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;

interface ActionInterface
{
    public function validate(Request $request): ActionInterface;

    public function process(Request $request, Response $response): Response;
}
