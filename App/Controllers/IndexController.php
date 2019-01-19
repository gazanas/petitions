<?php

namespace App\Controllers;

use App\Models\Petitions;
use App\Src\BaseController;

class IndexController extends BaseController
{
    
    /**
     * Handles the get request by the user.
     * 
     */
    public function show() : void
    {   
        $this->render('index.php');
    }
    
    public function get() : void
    {
        $this->toJSON((new Petitions($this->database))->getPetitions());
    }
    
    /**
     * Handles the post request by the user.
     *
     */
    public function add() : void
    {
        $input = $this->request->all();
        if(!$this->validatePost($input))
            return;
            
        (new Petitions($this->database))->createPetition($input['title'], $input['image'], $input['goal'], $input['summary']);
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