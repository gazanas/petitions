<?php

namespace App\Src;

class Router 
{
    private static $request;
    private static $getRoutes = [];
    private static $postRoutes = [];    
    
    /**
     * Register a new route
     * When no method can be resolved from the call the __callStatic is called
     *  
     * @param string $request
     * @param array $args
     */
    public static function __callStatic(string $request, array $args) : void
    {
        self::$request = new Request();
        
        list($route, $callback) = $args;
        
        if(!self::$request->isSupportedMethod(strtoupper($request)))
        {
            header(self::$request->protocol().' 405 Method Not Allowed');
            die();
        }
        
        self::${$request.'Routes'}['/'.trim($route, '/')] = $callback;
        
    } 
    
    /**
     * Executes the router
     * 
     */
    public static function execute() : void
    {
        if (self::$request->isMethod('get'))
        {
            $route = self::routeCallback(self::$getRoutes);
            list($controller, $method) = explode('::', self::$getRoutes[$route]);
        } else if (self::$request->isMethod('post'))
        {
            $route = self::routeCallback(self::$postRoutes);
            list($controller, $method) = explode('::', self::$postRoutes[$route]);
        } else 
        {
            header(self::$request->protocol().' 405 Method Not Allowed');
            die();
        }
        
        $resource = self::uriResource($route);
        
        $controller = 'App\\Controllers\\'.$controller;
        $controller = new $controller(self::$request, $resource);
        ($resource == null) ? $controller->$method() : $controller->$method($resource);
    }
    
    /**
     * Check if the requested uri exists in the routes
     * If no route found throw 404 error
     * 
     * @param array $routePool
     * @return string
     */
    public static function routeCallback(array $routePool)
    {
        /**
         * Check all registered routes if
         * the request uri exists
         */
        foreach(array_keys($routePool) as $route)
        {
            /**
             * If the route contains a resource (only supports integer resources for now)
             * then replace it with the appropriated regex
             */
            $searchRoute = preg_replace('/\{(int)\}/', '\d+', $route);
            
            if(preg_match('#^'.$searchRoute.'$#', self::$request->uriPath()))   
                return $route;
        }
        
        header(self::$request->protocol().' 404 Not Found');
        die();
    }
    
    /**
     * Retrieve the uri path resource
     *  
     * @param string $route
     * @return mixed
     */
    private static function uriResource(string $route)
    {
        $pathArray = explode('/', $route);
        $index = array_search('{int}', $pathArray);
        
        return (int) explode('/', self::$request->uriPath())[$index];
    }
   
}