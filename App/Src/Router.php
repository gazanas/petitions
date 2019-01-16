<?php

namespace App\Src;

use App\Controllers\IndexController;
use App\Controllers\PetitionController;

class Router 
{
    
    private $database;

    public function __construct() 
    {
        $this->database = (new Bootstrap)->getConnection();
    }
    
    /**
     * Get action parameter from the uri.
     * 
     * @return mixed
     */
    public function actionURI()
    {
        $uri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $query = parse_url($uri, PHP_URL_PATH);
        switch($query)
        {
            case '':
                $action = '';
                break;
            default:
                $params = explode('/', $query);
                $action = $params[count($params)-1];
        }
        
        return $action;
    }
    
    /**
     * Check if parameter pid was passed to the query of the uri.
     * 
     * @return bool
     */
    public function requestedPetition() : bool
    {
        if(isset(Request::getParameters()['pid']))
            return true;
        
        return false;
    }
    
    /**
     * Route the request to the right controller.
     * 
     */
    public function route() : void
    {
        if ($this->requestedPetition())
            $controller = new PetitionController($this->database, (int) Request::getParameters()['pid']);
        else 
            $controller = new IndexController($this->database);
        
        if (Request::isGet())
            $controller->handleGetRequest($this->actionURI(), Request::getParameters());
        else if (Request::isPost())
            $controller->handlePostRequest($this->actionURI(), Request::getParameters());
    }
}