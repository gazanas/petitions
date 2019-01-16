<?php

namespace App\Controllers;

use App\Models\Petitions;
use App\Models\Votes;

define('TIME_LIMIT', 20);

class PetitionController extends BaseController
{
    protected $database;
    protected $petition;
    protected $pid;
    
    public function __construct(\PDO $database, int $pid)
    {
        $this->database = $database;
        $this->pid = $pid;
        $this->petition = new Petitions($this->database);
        $this->votes = new Votes($this->database);
    }
    
    /**
     * Handles the get request by the user.
     *
     * {@inheritDoc}
     * @see \App\Controllers\BaseController::handleRequest()
     */
    public function handleGetRequest(string $action = null, array $input = []) : void
    {
        switch ($action)
        {
            case 'get_petition':
                $this->toJSON($this->petition->getPetition($this->pid));
                break;
            case 'get_new_votes':
                $this->awaitingVotes($this->pid);
                break;
            case 'get_votes':
                $goalPerc = round((count($this->votes->getVotes($this->pid))/$this->petition->getGoal($this->pid))*100, 2);
                $last = $this->votes->getLastVote($this->pid);
                (empty($last)) ? $this->toJSON(['votes' => 0]) : $this->toJSON(['votes' => $goalPerc, 'last' => $last['name'], 'country' => $last['country']]);
                break;
            default:
                $this->render(self::VIEW_ROOT.'petition.php');
        }
    }
    
    /**
     * Handles the post request by the user.
     *
     * {@inheritDoc}
     * @see \App\Controllers\BaseController::handleRequest()
     */
    public function handlePostRequest(string $action = null, array $input = []) : void
    {
        if(!$this->validatePost($input))
            return;
                
        $this->votes->vote($input['pid'], $input['name'], $input['email'], $input['country']);
        $this->toJSON(['success' => true]);
    }
    
    /**
     * Long petitioning new votes, waits and returns new votes if database status changes.
     * 
     */
    public function awaitingVotes() : void
    {
        $last = $this->votes->getLastVote($this->pid);
        $timestamp = (empty($last)) ? date('Y-m-d H:i:s') : $last['created_at'];
        $starting_point = time();
            
        while (!$this->votes->hasNewVotes($this->pid, $timestamp))
        {
            sleep(2);
            
            if ((time() - $starting_point) > TIME_LIMIT)
            {
                $this->toJSON(['timeout' => true]);
                return;
            } 
        }
        
        $goalPerc = round((count($this->votes->getVotes($this->pid))/$this->petition->getGoal($this->pid))*100, 2);
        $last = $this->votes->getLastVote($this->pid);
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
    public function validatePost(array $input) : bool
    {
        $required = ['name', 'email', 'country'];
        if($this->isEmpty($required, $input))
        {
            return false;
        } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_EMAIL))
        {
            $this->invalidEmail();
            return false;
        } else if ($this->votes->isDuplicate($input['email'], $input['pid']))
        {
            $this->duplicateEmail();
            return false;
        }
        
        return true;
    }
}