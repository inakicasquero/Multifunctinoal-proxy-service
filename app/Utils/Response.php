<?php

namespace App\Utils;

class Response
{
    public static function redirect($response, $to)
    {
        return $response->withStatus(302)->withHeader('Location', $to);
    }
}
