<?php

namespace App\Src;

class Request implements RequestInterface
{
    private $supportedMethods;
    private $uri;
    
    public function __construct()
    {
        $this->boot();
    }
    
    public function boot() : void
    {
        $this->supportedMethods = ['GET', 'POST'];
        
        $this->uri = ($this->isSecure() ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $this->accessInput();
    }
    
    public function uriPath() : string
    {
        return parse_url($this->uri, PHP_URL_PATH);
    }
    
    public function fullUri() : string
    {
        return $this->uri;
    }
    
    public function uriQuery() : string
    {
        return parse_url($this->uri, PHP_URL_QUERY);
    }
    
    public function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public function isSupportedMethod() : bool
    {
        if(in_array($this->method(), $this->supportedMethods))
            return true;
        return false;
    }
    
    public function protocol() : string 
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }
    
    public function isMethod(string $method) : bool
    {
        if($this->method() === strtoupper($method))
            return true;
        
        return false;
    }
    
    public function all() : array 
    {
        if($this->isMethod('get'))
            return $this->sanitizeInput($_GET);
        else if ($this->isMethod('post'))
            return $this->sanitizeInput($_POST);
    }
    
    public function isSecure()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            return true;
        return false;
    }
    
    private function accessInput() : void
    {
        if ($this->isMethod('get'))
            foreach($_GET as $key => $value)
                $this->{$key} = filter_var($value, FILTER_SANITIZE_STRING);
        else if ($this->isMethod('post'))
            foreach($_POST as $key => $value)
                $this->{$key} = filter_var($value, FILTER_SANITIZE_STRING);
    }
    
    private function sanitizeInput(array $inputs) : array
    {
        return array_map(function($input)
                         {
                            return filter_var($input, FILTER_SANITIZE_STRING);
                         }, $inputs);
    }
}