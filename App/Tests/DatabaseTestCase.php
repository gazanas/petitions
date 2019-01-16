<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__,2).'/config.php';

abstract class DatabaseTestCase extends TestCase
{
    
    final public function getDatabaseConnection()
    {
        try {
            $pdo = (new \PDO(DBMS.':host='.HOST.';', USER, PASSWORD));
        } catch(\PDOException $e)
        {
            die($e->getMessage());
        }
        return $pdo;
    }
    
    final public function createSchema(\PDO $database)
    {
        try 
        {
            $database->exec('CREATE DATABASE test;');
            $database->exec('USE test');
            $database->exec('CREATE TABLE petitions(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                                    title varchar(20) NOT NULL, 
                                                    image varchar(255) NOT NULL,
                                                    goal int NOT NULL, 
                                                    summary varchar(200) NOT NULL,
                                                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP)');
            
            $database->exec('CREATE TABLE votes(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                                     pid int(11) NOT NULL,
                                                     name varchar(50) NOT NULL,
                                                     email varchar(254) NOT NULL,
                                                     country varchar(25) NOT NULL,
                                                     created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                     FOREIGN KEY (pid) REFERENCES petitions(id))');
        } catch(\PDOException $e)
        {
            die($e->getMessage());
        }
    }
    
    final public function populateSchema(\PDO $database)
    {
        try
        {
            $database->exec("INSERT INTO petitions VALUES(null, 'Test Poll', 'test.jpg', 150, 'test summary', '2019-01-01 00:00:00')");
        } catch(\PDOException $e)
        {
            die($e->getMessage());
        }
    }
    
    final public function destroySchema(\PDO $database)
    {
        try
        {
            $database->exec('DROP DATABASE test;');
        } catch(\PDOException $e)
        {
            die($e->getMessage());
        }
    }
}