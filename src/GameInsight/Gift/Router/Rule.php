<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Router\Interfaces\RuleInterface;

class Rule implements RuleInterface
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

    public function getAction(Request $request): ActionInterface
    {
        $request->setParams($this->getMatchParams($request));
        return $this->action;
    }
}
