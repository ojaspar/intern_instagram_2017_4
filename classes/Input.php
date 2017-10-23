<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/17/17
 * Time: 5:11 PM
 */

class Input
{
    public static function exists($value = 'post')
    {
        switch ($value) {
            case 'post':
                return (!empty($_POST));
                break;
            case 'get';
                return (!empty($_GET));
                break;
            default:
                return false;
        }
    }

    public static function get($key)
    {
        if(isset($_POST[$key])) {
            return $_POST[$key];
        } else if(isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return '';
        }
    }
}