<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Action\ActionInterface;
use GameInsight\Gift\Action\AbstractAction;
use GameInsight\Gift\Http\Request;

class Rule
{
    protected $method;
    protected $uriRegExp;
    protected $actionClass;

    public function __construct(
        string $method,
        string $uriRegExp,
        ActionInterface $action
    ) {
        $this->method = $method;
        $this->uriRegExp = $uriRegExp;
        $this->action = $action;
    }

    public function isMatch(Request $request): bool
    {
        return ($request->getMethod() == $this->method && boolval(preg_match($this->uriRegExp, $request->getUri())));
    }

    public function getMatchParams(Request $request): array
    {
        $params = [];
        preg_match($this->uriRegExp, $request->getUri(),$params);
        return $params;
    }

    public function getAction(Request $request): AbstractAction
    {
        $request->setParams($this->getMatchParams($request));
        return $this->action;
    }
}
