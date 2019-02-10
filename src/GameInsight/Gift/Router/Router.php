<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Exceptions\NotFound;
use GameInsight\Gift\Router\Interfaces\RuleInterface;

/**
 * Class Router
 * @package GameInsight\Gift\Router
 */
class Router
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @param RuleInterface $rule
     * @return Router
     */
    public function addRule(RuleInterface $rule): Router
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * @param Request $request
     * @return ActionInterface
     * @throws NotFound
     */
    public function route(Request $request): ActionInterface
    {
        foreach ($this->rules as $rule) {
            if ($rule->isMatch($request)) {
                return $rule->getAction($request);
            }
        }
        throw new NotFound();
    }
}
