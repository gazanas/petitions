<?php

namespace App\Models;

class Petitions 
{
    private $database;
    
    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }
    
    /**
     * Get the petition info from the database.
     * 
     * @return array
     */
    public function getPetitions() : array
    {
        $statement = $this->database->prepare('SELECT id, title, image, summary FROM petitions ORDER BY id DESC');
        $statement->execute();
        
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $statement = null;
        
        return (empty($result)) ? [] : $result;
    }
    
    /**
     * Get one petitions info.
     * 
     * @param int $id
     * 
     * @return array
     */
    public function getPetition(int $id) : array
    {
        $statement = $this->database->prepare('SELECT id, title, image, summary FROM petitions WHERE id = ?');
        $statement->bindParam(1, $id, \PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        $statement = null;
        
        return (empty($result)) ? [] : $result;
    }
    
    /**
     * Create new petition.
     * 
     * @param string $title
     * @param string $image
     * @param int $goal
     * @param string $summary
     */
    public function createPetition(string $title, string $image, int $goal, string $summary) : void
    {
        $statement = $this->database->prepare('INSERT INTO petitions(id, title, image, goal, summary, created_at) VALUES(null, ?, ?, ?, ?, null)');
        $statement->bindParam(1, $title, \PDO::PARAM_STR);
        $statement->bindParam(2, $image, \PDO::PARAM_STR);
        $statement->bindParam(3, $goal, \PDO::PARAM_INT);
        $statement->bindParam(4, $summary, \PDO::PARAM_STR);
        
        $statement->execute();
        
        $statement = null;
    }
    
    /**
     * Get petitions goal.
     * 
     * @param int $pid
     * 
     * @return int
     */
    public function getGoal(int $pid) : int
    {
        $statement = $this->database->prepare('SELECT goal FROM petitions WHERE id = ?');
        $statement->bindParam(1, $pid, \PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $statement = null;
        
        return $result['goal'];
    }
}