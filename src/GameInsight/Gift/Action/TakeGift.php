<?php
declare(strict_types=1);

namespace GameInsight\Gift\Action;

use GameInsight\Gift\Http\Request;
use GameInsight\Gift\Http\Response;

class TakeGift extends AbstractAction implements ActionInterface
{
    public function process(Request $request, Response $response): Response
    {
        $this->gift->take(
            $request->getParamValue('user_id'),
            intval($request->getPostJsonValue('id'))
        );
        return $response
            ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 200 OK', true, 200)
            ->addHeader('Status: 200 OK', true, 200)
            ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
            ->addHeader('Content-Type: application/json')
            ->setBodyAsArray([
                'status' => 0,
            ]);
    }
}
