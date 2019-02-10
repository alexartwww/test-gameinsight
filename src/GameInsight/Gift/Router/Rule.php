<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Router\Interfaces\RuleInterface;

/**
 * Class Rule
 * @package GameInsight\Gift\Router
 */
class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $method;
    /**
     * @var string
     */
    protected $uriRegExp;
    /**
     * @var
     */
    protected $actionClass;

    /**
     * Rule constructor.
     * @param string $method
     * @param string $uriRegExp
     * @param ActionInterface $action
     */
    public function __construct(
        string $method,
        string $uriRegExp,
        ActionInterface $action
    ) {
        $this->method = $method;
        $this->uriRegExp = $uriRegExp;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUriRegExp(): string
    {
        return $this->uriRegExp;
    }

    /**
     * @param string $uriRegExp
     */
    public function setUriRegExp(string $uriRegExp)
    {
        $this->uriRegExp = $uriRegExp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionClass()
    {
        return $this->actionClass;
    }

    /**
     * @param mixed $actionClass
     */
    public function setActionClass($actionClass)
    {
        $this->actionClass = $actionClass;
        return $this;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function isMatch(Request $request): bool
    {
        return ($request->getMethod() == $this->method && boolval(preg_match($this->uriRegExp, $request->getUri())));
    }

    /**
     * @param Request $request
     * @return array
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function getMatchParams(Request $request): array
    {
        $params = [];
        preg_match($this->uriRegExp, $request->getUri(),$params);
        return $params;
    }

    /**
     * @param Request $request
     * @return ActionInterface
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function getAction(Request $request): ActionInterface
    {
        $request->setParams($this->getMatchParams($request));
        return $this->action;
    }
}
