<?php

namespace App\Controllers;

use App\Models\Petitions;
use App\Models\Votes;
use App\Src\BaseController;

define('TIME_LIMIT', 20);

class PetitionController extends BaseController
{   
    public function show()
    {
        $this->render('petition.php');
    }
    
    public function getPetition(int $pid)
    {
        $this->toJSON((new Petitions($this->database))->getPetition($pid));
    }
    
    public function getNewVotes(int $pid)
    {
        $this->awaitingVotes((new Votes($this->database)), (new Petitions($this->database)), $pid);
    }
    
    public function getVotes(int $pid)
    {
        $votes = (new Votes($this->database));
        $goalPerc = round((count($votes->getVotes($pid))/(new Petitions($this->database))->getGoal($pid))*100, 2);
        $last = $votes->getLastVote($pid);
        (empty($last)) ? $this->toJSON(['votes' => 0]) : $this->toJSON(['votes' => $goalPerc, 'last' => $last['name'], 'country' => $last['country']]);
    }
    
    /**
     * Handles the post request by the user.
     *
     */
    public function vote(int $pid) : void
    {
        $input = $this->request->all();
        $votes = (new Votes($this->database));
        
        if(!$this->validatePost($votes, $input, $pid))
            return;
                
        $votes->vote($pid, $input['name'], $input['email'], $input['country']);
        $this->toJSON(['success' => true]);
    }
    
    /**
     * Long petitioning new votes, waits and returns new votes if database status changes.
     * 
     */
    public function awaitingVotes(Votes $votes, Petitions $petition, int $pid) : void
    {
        $last = $votes->getLastVote($pid);
        $timestamp = (empty($last)) ? date('Y-m-d H:i:s') : $last['created_at'];
        $starting_point = time();
            
        while (!$votes->hasNewVotes($pid, $timestamp))
        {
            sleep(2);
            
            if ((time() - $starting_point) > TIME_LIMIT)
            {
                $this->toJSON(['timeout' => true]);
                return;
            } 
        }
        
        $goalPerc = round((count($votes->getVotes($pid))/$petition->getGoal($pid))*100, 2);
        $last = $votes->getLastVote($pid);
        $this->toJSON(['votes' => $goalPerc, 'last' => $last['name'], 'country' => $last['country']]);
    }
    
    /**
     * Is email valid
     * 
     */
    public function invalidEmail() : void
    {
        $this->toJSON(['error' => true, 'type' => 'email-error', 'message' => 'Invalid Email Address']);
    }
    
    /**
     * Is email duplicate
     * 
     */
    public function duplicateEmail() : void
    {
        $this->toJSON(['error' => true, 'type' => 'email-error', 'message' => 'Email Address Alreay Signed']);
    }
    
    /**
     * Validate the input values of the post request.
     *
     * @return bool
     */
    public function validatePost(Votes $votes, array $input, int $pid) : bool
    {
        $required = ['name', 'email', 'country'];
        if($this->isEmpty($required, $input))
        {
            return false;
        } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_EMAIL))
        {
            $this->invalidEmail();
            return false;
        } else if ($votes->isDuplicate($input['email'], $pid))
        {
            $this->duplicateEmail();
            return false;
        }
        
        return true;
    }
}