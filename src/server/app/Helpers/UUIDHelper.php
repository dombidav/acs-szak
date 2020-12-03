<?php


namespace App\Helpers;

class UUIDHelper
{
    public static function generate()
    {
        return uniqid(dechex(time() - strtotime('2000-01-01 00:00:01')));
    }
}
