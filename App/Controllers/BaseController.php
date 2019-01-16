<?php

namespace App\Controllers;

define('VIEW_ROOT', dirname(__DIR__).'/Views/');

abstract class BaseController {
    
    const VIEW_ROOT = VIEW_ROOT;
    
    public abstract function handleGetRequest(string $action = null, array $input = []);
    public abstract function handlePostRequest(string $action = null, array $input = []);
   
    /**
     * Does the input comply with the required parameters 
     * 
     * @param array $required
     * @param array $input
     * 
     * @return boolean
     */
    public function isEmpty(array $required, array $input) : bool
    {
        foreach ($required as $field)
        {
            if (!isset($input[$field]) || empty($input[$field]))
            {
                $this->toJSON(['error' => true, 'type' => $field.'-error', 'message' => 'Please Complete '.ucfirst($field)]);
                return true;
            }            
        }
        
        return false;
    }
    
    /**
     * Render an html view
     * 
     * @param string $view
     */
    public function render(string $view) :void
    {
        print(file_get_contents($view));   
    }
    
    /**
     * Print JSON encoded response
     * 
     * @param array $response
     */
    public function toJSON(array $response) : void
    {
        print(json_encode($response));
    }
}