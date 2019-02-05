<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;

class SendGift extends AbstractAction implements ActionInterface
{
    public function process(Request $request, Response $response): Response
    {
        $this->gift->send(
            $request->getParamValue('user_id'),
            intval($request->getParamValue('day_id')),
            $request->getPostJsonValue('friend_id'),
            intval($request->getPostJsonValue('gift_id'))
        );
        return $response
            ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 201 Created', true, 201)
            ->addHeader('Status: 201 Created', true, 201)
            ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
            ->addHeader('Content-Type: application/json')
            ->setBodyAsArray([
                'status' => 0,
            ]);
    }
}
