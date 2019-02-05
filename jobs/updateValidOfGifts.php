<?php
require __DIR__ . '/../vendor/autoload.php';

use \GameInsight\Gift\Domain\Gift;

try {
    $dsn = 'mysql:dbname=gameinsightgift;host=mysql';
    $user = 'gameinsightgift';
    $password = '1234';
    (new Gift(new \PDO($dsn, $user, $password)))->expire(intval(time()/86400));
} catch (\Exception $exception) {
    error_log($exception->getCode().' '.$exception->getMessage());
}
