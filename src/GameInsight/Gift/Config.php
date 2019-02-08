<?php

namespace GameInsight\Gift;

class Config
{
    public static $db = [
        'dsn' => 'mysql:dbname=gameinsightgift;host=mysql',
        'user' => 'gameinsightgift',
        'password' => '1234',
    ];

    public static $auth = 'secRetc0de';

    public static $expireDays = 7;
}
