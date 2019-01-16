<?php 

require_once('vendor/autoload.php');

use App\Src\Router;

class Initialize 
{
    
    public function setup()
    {        
        (new Router())->route();
    }
}

(new Initialize())->setup();

?>