<?php
require __DIR__ . '/../vendor/autoload.php';

use GameInsight\Gift\Config;
use GameInsight\Gift\Domain\Gift;

try {
    (new Gift(new \PDO(Config::$db['dsn'], Config::$db['user'], Config::$db['password']), Config::$expireDays))
        ->expire(intval(time()/86400));
} catch (\Exception $exception) {
    error_log($exception->getCode().' '.$exception->getMessage());
}
