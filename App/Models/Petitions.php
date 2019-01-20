<?php

namespace App\Models;

use App\Src\Model;

class Petitions extends Model
{
    /**
     * Get the petition info from the database.
     *
     * @return array
     *
     */
    public function getPetitions() : array
    {
        return $this->get('petitions', ['return' => ['id', 'title', 'image', 'summary'], 'options' => ['order' => 'id DESC']]);
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
        $result = $this->get('petitions', ['return' => ['id', 'title', 'image', 'summary'], 'id' => $id]);
        
        return (empty($result)) ? [] : $result[0];
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
        $this->insert('petitions', ['title' => $title, 'image' => $image, 'goal' => $goal, 'summary' => $summary]);
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
        return $this->get('petitions', ['return' => ['goal'], 'id' => $pid])[0]['goal'];
        
    }
}