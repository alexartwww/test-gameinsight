<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Router\BadRequest;
use GameInsight\Gift\Validator\ValidatorCollection;
use GameInsight\Gift\Validator\ValidationErrorCollection;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Domain\Gift;

abstract class AbstractAction
{
    protected $gift;
    protected $validatorCollection;

    public function __construct(Gift $gift, ValidatorCollection $validatorCollection)
    {
        $this->gift = $gift;
        $this->validatorCollection = $validatorCollection;
    }

    public function validate(Request $request): AbstractAction
    {
        $validationErrors = new ValidationErrorCollection();
        foreach ($this->validatorCollection as $validator) {
            if (!$validator->isValid($request)) {
                $validationErrors->add($validator->getError());
            }
        }
        if (iterator_count($validationErrors)) {
            throw new BadRequest("Bad Request", 400, null, $validationErrors);
        }
        return $this;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     * @throws \Exception
     */
    public function process(Request $request, Response $response): Response
    {
        return $response;
    }
}