<?php 

require_once('vendor/autoload.php');

use App\Src\Router;

class Initialize 
{
    
    public function setup()
    {     
        require_once(__DIR__.'/App/Routes/Routes.php');
        Router::execute();
    }
}

(new Initialize())->setup();

?>