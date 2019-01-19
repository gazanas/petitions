<?php

namespace App\Models;

use App\Src\Model;

class Votes extends Model
{
    
    /**
     * Get the votes of a petition
     * 
     * @param int $pid
     
     * @return array
     */
    public function getVotes(int $pid) : array
    {
        return $this->get('votes', ['return' => ['name', 'country'], 'pid' => $pid]);
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
       $this->insert('votes', ['pid' => $pid, 'name' => $name, 'email' => $email, 'country' => $country]);
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
        $result = $this->get('votes', ['pid' => $pid, 'created_at' => $created_at]);
        
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
        $result = $this->get('votes', ['pid' => $pid, 'options' => ['order' => 'created_at DESC', 'limit' => 1]]);
        return (empty($result)) ? [] : $result[0];
        
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
        $result = $this->get('votes', ['pid' => $pid, 'email' => $email]);
        
        return (empty($result)) ? false : true;
    }
}