<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/19/17
 * Time: 11:15 AM
 */

class Redirect
{
    public static function to($location)
    {
        if(is_numeric($location)) {
            switch($location) {
                case 404:
                    header('HTTP/1.0 404 Not Found');
                    include 'includes/errors/' . $location . '.php';
                    exit();
                    break;
            }
        }
        header('Location: ' . $location);
        exit();
    }

}