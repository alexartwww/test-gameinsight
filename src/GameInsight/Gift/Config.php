<?php

namespace GameInsight\Gift;

/**
 * Class Config
 * @package GameInsight\Gift
 */
class Config
{
    /**
     * @var array
     */
    public static $db = [
        'dsn' => 'mysql:dbname=gameinsightgift;host=mysql',
        'user' => 'gameinsightgift',
        'password' => '1234',
    ];

    /**
     * @var string
     */
    public static $auth = 'secRetc0de';

    /**
     * @var int
     */
    public static $expireDays = 7;
}
