<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/18/17
 * Time: 1:27 PM
 */

class Token
{
    public static function generate()
    {
        $tokenName = Config::get('session/token_name');
        $token = Hash::unique();
        Session::put($tokenName, $token);
        return $token;
    }

    public static function check($token)
    {
        $tokenName = Config::get('session/token_name');
        return ($_SESSION[$tokenName] === $token);
    }
}