<?php
require __DIR__ . '/../vendor/autoload.php';

use \GameInsight\Gift\Action\GetGifts;
use \GameInsight\Gift\Action\SendGift;
use \GameInsight\Gift\Action\TakeGift;
use \GameInsight\Gift\Validator\ValidatorCollection;
use \GameInsight\Gift\Validator\GiftValidator;
use \GameInsight\Gift\Validator\UserValidator;
use \GameInsight\Gift\Validator\FriendValidator;
use \GameInsight\Gift\Validator\DayValidator;
use \GameInsight\Gift\Validator\AuthValidator;
use \GameInsight\Gift\Router\Router;
use \GameInsight\Gift\Router\Rule;
use \GameInsight\Gift\Http\Request;
use \GameInsight\Gift\Http\Response;
use \GameInsight\Gift\Router\BadRequest;
use \GameInsight\Gift\Router\NotFound;
use \GameInsight\Gift\Domain\GiftException;
use \GameInsight\Gift\Domain\Gift;

try {
    $request = new Request($GLOBALS, $_SERVER, $_REQUEST, $_POST, $_GET, $_FILES, $_ENV, $_COOKIE, $_SESSION);
    $response = new Response();

    $dsn = 'mysql:dbname=gameinsightgift;host=mysql';
    $user = 'gameinsightgift';
    $password = '1234';
    $gift = new Gift(new \PDO($dsn, $user, $password));

    $sendGiftAction = new SendGift(
        $gift,
        (new ValidatorCollection())
            ->add(new AuthValidator())
            ->add(new DayValidator())
            ->add(new UserValidator())
            ->add(new FriendValidator())
            ->add(new GiftValidator())
    );
    $getGiftsAction = new GetGifts(
        $gift,
        (new ValidatorCollection())
            ->add(new UserValidator())
            ->add(new GiftValidator())
    );
    $takeGiftAction = new TakeGift(
        $gift,
        (new ValidatorCollection())
            ->add(new AuthValidator())
            ->add(new UserValidator())
            ->add(new GiftValidator())
    );

    (new Router())
        ->addRule(new Rule('POST', '#^/gifts/(?P<user_id>[0-9a-zA-Z\-]+)/(?P<day_id>[0-9]+)$#', $sendGiftAction))
        ->addRule(new Rule('GET', '#^/gifts/(?P<user_id>[0-9a-zA-Z\-]+)$#', $getGiftsAction))
        ->addRule(new Rule('PUT', '#^/gifts/(?P<user_id>[0-9a-zA-Z\-]+)$#', $takeGiftAction))
    ->route($request)
        ->validate($request)
            ->process($request, $response)
                ->send();
} catch (GiftException $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 400 Bad Request', true, 400)
        ->addHeader('Status: 400 Bad Request', true, 400)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyAsArray(['status' => $exception->getCode(), 'message' => $exception->getMessage()])
        ->send();
} catch (BadRequest $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 400 Bad Request', true, 400)
        ->addHeader('Status: 400 Bad Request', true, 400)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyAsArray(['status' => 400, 'message' => $exception->getMessage()])
        ->send();
} catch (NotFound $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 404 Not Found', true, 404)
        ->addHeader('Status: 404 Not Found', true, 404)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyAsArray(['status' => 404, 'message' => $exception->getMessage()])
        ->send();
} catch (\Exception $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 500 Internal Server Error', true, 500)
        ->addHeader('Status: 500 Internal Server Error', true, 500)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyAsArray(['status' => 500, 'message' => $exception->getMessage()])
        ->send();
}
