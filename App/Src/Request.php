<?php

namespace App\Src;

class Request 
{
    /**
     * Return the parameters passed on the request.
     */
    public static function getParameters()
    {
        if(self::isGet())
            return $_GET;
        else if (self::isPost())
            return $_POST;
        
        die(['error' => true, 'message' => 'Invalid Request']);
        return;
    }
    
    /**
     * Is a post request
     *
     * @return bool
     */
    public static function isPost() : bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            return true;
            
        return false;
    }
    
    /**
     * Is a get request
     *
     * @return bool
     */
    public static function isGet() : bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
            return true;
            
        return false;
    }
}