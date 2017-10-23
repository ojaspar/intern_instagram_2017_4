<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/18/17
 * Time: 1:27 PM
 */

class Session
{
    public static function exists($name)
    {
        return (isset($_SESSION[$name]));
    }

    public static function get($name)
    {
        if(self::exists($name)) {
            return $_SESSION[$name];
        }
        return false;

    }

    public static function put($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function delete($name)
    {
        unset($_SESSION[$name]);
    }

    public static function flash($name, $string = '')
    {
        $message = '';
        if(self::exists($name)) {
            $message = self::get($name);
            self::delete($name);
            return $message;
        }
        self::put($name, $string);
        return $message;
    }
}