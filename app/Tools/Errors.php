<?php

namespace App\Tools;

class Errors
{
    const
        NOT_AUTHORIZED = 401,
        NOT_PERMITTED = 403,
        NOT_FOUND = 404,
        LIMIT = 416,
        ACCESS_TOKEN = 419,
        VALIDATION = 422,
        GUEST = 424,
        SERVER = 500;

    /**
     * @var array
     */
    public static $messages = [
        self::NOT_AUTHORIZED => 'Not Authorized',
        self::NOT_PERMITTED  => 'Not Permitted',
        self::NOT_FOUND      => 'Not Found',
        self::LIMIT          => 'Limit Exceeded',
        self::ACCESS_TOKEN   => 'Token Check Error',
        self::VALIDATION     => 'Validation Error',
        self::GUEST          => 'Already Authorized',
        self::SERVER         => 'Server Error'
    ];
}
