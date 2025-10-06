<?php

namespace Core\Session;

class Session
{

    public static function start()
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }

    public static function get($key)
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }else{
            return false;
        }
    }


    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function remove($index)
    {
        if(isset($_SESSION[$index]))
        {
            unset($_SESSION[$index]);
        }
    }

    public static function userLoggedIn():bool
    {
        if(Session::get("user"))
        {
            return true;
        }else{
            return false;
        }
    }






}