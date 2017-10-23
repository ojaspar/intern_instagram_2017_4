<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/18/17
 * Time: 1:45 PM
 */

class Hash
{
    public static function make($value, $salt = '')
    {
        return hash('sha256', $value . $salt);
    }

    public static function salt($length = 10)
    {
        return random_bytes($length);
    }

    public static function unique()
    {
        return self::make(uniqid());
    }
}