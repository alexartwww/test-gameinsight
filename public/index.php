<?php
require __DIR__ . '/../vendor/autoload.php';

use \GameInsight\Gift\Config;
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
    $request = new Request($_SERVER, $_POST, $_GET, $_COOKIE, file_get_contents('php://input'));
    $response = new Response();

    $gift = new Gift(new \PDO(Config::$db['dsn'], Config::$db['user'], Config::$db['password']), Config::$expireDays);

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
        ->setBodyJson(['status' => $exception->getCode(), 'message' => $exception->getMessage()])
        ->send();
} catch (BadRequest $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 400 Bad Request', true, 400)
        ->addHeader('Status: 400 Bad Request', true, 400)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyJson(['status' => 400, 'message' => $exception->getMessage()])
        ->send();
} catch (NotFound $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 404 Not Found', true, 404)
        ->addHeader('Status: 404 Not Found', true, 404)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyJson(['status' => 404, 'message' => $exception->getMessage()])
        ->send();
} catch (\Exception $exception) {
    $response
        ->addHeader($request->getServerValue('SERVER_PROTOCOL').' 500 Internal Server Error', true, 500)
        ->addHeader('Status: 500 Internal Server Error', true, 500)
        ->addHeader('Cache-Control: no-cache,no-store,max-age=0,must-revalidate')
        ->addHeader('Content-Type: application/json')
        ->setBodyJson(['status' => 500, 'message' => $exception->getMessage()])
        ->send();
}
