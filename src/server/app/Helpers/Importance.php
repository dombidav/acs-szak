<?php


namespace app\Helpers;


use ReflectionClass;

class Importance
{
    const NOTICE = 0;
    const WARNING = 1;
    const ERROR = 2;

    public static function From($any){
        foreach (self::getConstants() as $key => $value){
            if($value == $any) {
                return $key;
            }
        }
        return null;
    }

    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
