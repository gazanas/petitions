<?php

namespace App\Models;

class Votes
{
    
    private $database;
    
    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }
    
    /**
     * Get the votes of a petition
     * 
     * @param int $pid
     
     * @return array
     */
    public function getVotes(int $pid) : array
    {
        $statement = $this->database->prepare('SELECT name, country FROM votes WHERE pid = ?');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement = null;

        return (empty($result)) ? [] : $result;
    }
    
    /**
     * Create a new petition vote.
     * 
     * @param int $pid
     * @param string $name
     * @param string $email
     * @param string $country
     */
    public function vote(int $pid, string $name, string $email, string $country) : void
    {
        $statement = $this->database->prepare('INSERT INTO votes(id, pid, name, email, country, created_at) VALUES(NULL, ?, ?, ?, ?, NULL)');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->bindParam(2, $name, \PDO::PARAM_STR);
        $statement->bindParam(3, $email, \PDO::PARAM_STR);
        $statement->bindParam(4, $country, \PDO::PARAM_STR);
        
        $statement->execute();
        $statement = null;
    }
    
    /**
     * Compare dates to find new votes.
     * 
     * @param int $pid
     * @param string $created_at
     * 
     * @return bool
     */
    public function hasNewVotes(int $pid, string $created_at) : bool
    {
        $statement = $this->database->prepare('SELECT * FROM votes WHERE pid = ? AND created_at > ?');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->bindParam(2, $created_at, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        $statement = null;
        
        return (empty($result)) ? false : true;
    }
    
    /**
     * Get the last inserted vote.
     * 
     * @param int $pid
     * 
     * @return array
     */
    public function getLastVote(int $pid) : array
    {
        $statement = $this->database->prepare('SELECT * FROM votes WHERE pid = ? ORDER BY created_at DESC LIMIT 1');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        $statement = null;
        
        return (empty($result)) ? [] : $result;
        
    }
    
    /**
     * Is the email duplicate.
     * 
     * @param string $email
     * @param int $pid
     * 
     * @return bool
     */
    public function isDuplicate(string $email, int $pid) : bool
    {
        $statement = $this->database->prepare('SELECT * FROM votes WHERE pid = ? AND email = ?');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->bindParam(2, $email, \PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        $statement = null;
        
        return (empty($result)) ? false : true;
    }
}