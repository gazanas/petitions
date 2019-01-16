<?php

namespace App\Src;

require_once dirname(__DIR__,2).'/config.php';

class Bootstrap {
    
    public function __construct()
    {
        date_default_timezone_set('Europe/Athens');
    }
    
    /**
     * Connect to the database
     * 
     * @return \PDO
     */
    public static function getConnection()
    {    
        try
        {
            return (new \PDO(DBMS.':host='.HOST.';dbname='.DATABASE, USER, PASSWORD));
        }catch(\PDOException $e) {
            print($e->getMessage());
        }
    }
}