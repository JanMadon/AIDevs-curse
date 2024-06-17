<?php

namespace app\CodeTools;


class Validator 
{
    public static function isValidJson($string)
    {
        json_decode($string);

        return (json_last_error() === JSON_ERROR_NONE);
    }
}