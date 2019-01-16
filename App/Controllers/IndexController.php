<?php

namespace App\Controllers;

use App\Models\Petitions;

class IndexController extends BaseController
{
    protected $database;
    protected $petition;
    
    public function __construct(\PDO $database)
    {
        $this->database = $database;
        $this->petition = new Petitions($this->database);
    }
    
    /**
     * Handles the get request by the user.
     * 
     * {@inheritDoc}
     * @see \App\Controllers\BaseController::handleRequest()
     */
    public function handleGetRequest(string $action = null, array $input = []) : void
    {
        switch($action)
        {
            case 'get_petitions':
                $this->toJSON($this->petition->getPetitions());
                break;
            default:
                $this->render(self::VIEW_ROOT.'index.php');
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
            
        $this->petition->createPetition($input['title'], $input['image'], $input['goal'], $input['summary']);
        $this->toJSON(['success' => true]);
    }
    
    /**
     * Validate the input values of the post request.
     * 
     * @return bool
     */
    public function validatePost(array $input) : bool
    { 
        $required = ['title', 'image', 'goal', 'summary'];
        if($this->isEmpty($required, $input))
        {
            return false;
        } else if(!preg_match('/\.(jpg|jpeg|gif|png)(\?.*)?$/', $input['image']))
        {
            $this->toJSON(['error' => true, 'type' => 'image-error', 'message' => 'Image type is not valid']);
            return false;
            
        } else if(!filter_var($input['goal'], FILTER_VALIDATE_INT) || $input['goal'] <= 0)
        {
            $this->toJSON(['error' => true, 'type' => 'goal-error', 'message' => 'Goal must be an integer']);
            return false;
        }
        
        return true;
    }
}