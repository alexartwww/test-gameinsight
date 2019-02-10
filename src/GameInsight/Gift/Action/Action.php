<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Exceptions\BadRequest;
use GameInsight\Gift\Validator\ValidatorCollection;
use GameInsight\Gift\Validator\ValidationErrorCollection;
use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Domain\Interfaces\GiftInterface;
use GameInsight\Gift\Action\Interfaces\ActionInterface;

/**
 * Class Action
 * @package GameInsight\Gift\Action
 */
class Action
{
    /**
     * @var GiftInterface
     */
    protected $gift;
    /**
     * @var ValidatorCollection
     */
    protected $validatorCollection;

    /**
     * Action constructor.
     * @param GiftInterface $gift
     * @param ValidatorCollection $validatorCollection
     */
    public function __construct(GiftInterface $gift, ValidatorCollection $validatorCollection)
    {
        $this->gift = $gift;
        $this->validatorCollection = $validatorCollection;
    }

    /**
     * @return GiftInterface
     */
    public function getGift(): GiftInterface
    {
        return $this->gift;
    }

    /**
     * @param GiftInterface $gift
     */
    public function setGift(GiftInterface $gift)
    {
        $this->gift = $gift;
        return $this;
    }

    /**
     * @return ValidatorCollection
     */
    public function getValidatorCollection(): ValidatorCollection
    {
        return $this->validatorCollection;
    }

    /**
     * @param ValidatorCollection $validatorCollection
     */
    public function setValidatorCollection(ValidatorCollection $validatorCollection)
    {
        $this->validatorCollection = $validatorCollection;
        return $this;
    }

    /**
     * @param Request $request
     * @return ActionInterface
     * @throws BadRequest
     */
    public function validate(Request $request): ActionInterface
    {
        $validationErrors = new ValidationErrorCollection();
        foreach ($this->validatorCollection as $validator) {
            if (!$validator->isValid($request)) {
                $validationErrors->add($validator->getValidationError());
            }
        }
        if (iterator_count($validationErrors)) {
            throw new BadRequest("Bad Request " . strval($validationErrors), 400, null, $validationErrors);
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