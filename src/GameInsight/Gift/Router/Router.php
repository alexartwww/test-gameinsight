<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Action\Interfaces\ActionInterface;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Exceptions\NotFound;
use GameInsight\Gift\Router\Interfaces\RuleInterface;

class Router
{
    protected $rules = [];

    public function __construct()
    {
    }

    public function addRule(RuleInterface $rule): Router
    {
        $this->rules[] = $rule;
        return $this;
    }

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
