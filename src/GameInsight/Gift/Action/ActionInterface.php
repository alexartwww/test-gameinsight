<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;

interface ActionInterface
{
    public function validate(Request $request): AbstractAction;

    public function process(Request $request, Response $response): Response;
}
