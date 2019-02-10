<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Action\Interfaces\ActionInterface;

/**
 * Class TakeGift
 * @package GameInsight\Gift\Action
 */
class TakeGift extends Action implements ActionInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function process(Request $request, Response $response): Response
    {
        $this->gift->take(
            $request->getParamValue('user_id'),
            intval($request->getBodyJsonValue('id'))
        );
        return $response
            ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 200 OK', true, 200)
            ->addHeader('Status: 200 OK', true, 200)
            ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
            ->addHeader('Content-Type: application/json')
            ->setBodyJson([
                'status' => 0,
            ]);
    }
}
