<?php
declare(strict_types=1);

namespace GameInsight\Gift\Router;

use GameInsight\Gift\Action\ActionInterface;
use GameInsight\Gift\Http\Request;

class Router
{
    protected $rules = [];

    public function __construct()
    {
    }

    public function addRule(Rule $rule): Router
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
