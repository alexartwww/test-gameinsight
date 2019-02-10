<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;
use GameInsight\Gift\Action\Interfaces\ActionInterface;

/**
 * Class SendGift
 * @package GameInsight\Gift\Action
 */
class SendGift extends Action implements ActionInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \GameInsight\Gift\Http\Exceptions\BadRequest
     */
    public function process(Request $request, Response $response): Response
    {
        $this->gift->send(
            $request->getParamValue('user_id'),
            intval($request->getParamValue('day_id')),
            $request->getBodyJsonValue('friend_id'),
            intval($request->getBodyJsonValue('gift_id'))
        );
        return $response
            ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 201 Created', true, 201)
            ->addHeader('Status: 201 Created', true, 201)
            ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
            ->addHeader('Content-Type: application/json')
            ->setBodyJson([
                'status' => 0,
            ]);
    }
}
