<?php

namespace App\Tests;

use App\Controllers\IndexController;
use App\Src\Bootstrap;
use App\Controllers\PetitionController;
use App\Models\Votes;

class ControllerTest extends DatabaseTestCase
{
    private $controller;
    private $database;
    
    public function setUp()
    {
        $this->controller = new IndexController((new Bootstrap)->getConnection());
        $this->database = $this->getDatabaseConnection();
        $this->createSchema($this->database);
        $this->populateSchema($this->database);
    }
    
    public function tearDown()
    {
        $this->destroySchema($this->database);
    }
    
    public function testIsEmptyInput()
    {
        
        /**
         * Test if no input is given
         */
        $this->assertTrue($this->controller->isEmpty(['title', 'goal', 'image', 'summary'], []));
        
        /**
         * Test if input is missing
         */
        $this->assertTrue($this->controller->isEmpty(['title', 'goal', 'image', 'summary'], ['title' => 'Petition1', 'goal' => 150, 'summary' => 'Petition Summary']));
        
        /**
         * Test if input is correct
         */
        $this->assertFalse($this->controller->isEmpty(['title', 'goal', 'image', 'summary'], ['title' => 'Petition1', 'goal' => 150, 'image' => 'petition.jpg', 'summary' => 'Petition Summary']));
    }
    
    public function testGetPetitions()
    {
        $expected = json_encode([
          [
              'id' => '1',
              'title' => 'Test Poll',
              'image' => 'test.jpg',
              'summary' => 'test summary',
          ]  
        ]);
        
        $this->expectOutputString($expected);
        
        (new IndexController($this->database))->handleGetRequest('get_petitions');
    }
    
    public function testGetPetitionThatDoesntExist()
    {
        $expected = json_encode([
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 2))->handleGetRequest('get_petition');
    }
    
    public function testGetOnePetition()
    {
        $expected = json_encode([
            'id' => '1',
            'title' => 'Test Poll',
            'image' => 'test.jpg',
            'summary' => 'test summary',
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 1))->handleGetRequest('get_petition');
    }
    
    public function testCreateNewPetitionMissingAllInputsFails()
    {
        /**
         * Missing all input fields fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'title-error',
            'message' => 'Please Complete Title'
        ]);
               
        $this->expectOutputString($expected);
        $this->assertNull((new IndexController($this->database))->handlePostRequest(null, []));
        
    }
    
    public function testCreateNewPetitionMissingOneInputFails()
    {
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'image-error',
            'message' => 'Please Complete Image'
        ]);
                
        $this->expectOutputString($expected);
        $this->assertNull((new IndexController($this->database))->handlePostRequest(null, ['title' => 'Test Poll 2', 'goal' => 150, 'summary' => 'test summary 2']));
        
    }
    
    public function testCreateNewPetition()
    {  
        /**
         * Successful creation
         */
        $expected = json_encode([
            'success' => true
        ]);
        
        $this->expectOutputString($expected);
        
        (new IndexController($this->database))->handlePostRequest(null, ['title' => 'Test Poll 2', 'image' => 'test.jpg', 'goal' => 150, 'summary' => 'test summary 2']);
    }
    
    /**
     * Slows the tests down
     * 
    public function testGetNewVotesTimesOutAfterTenSeconds()
    {
        $expected = json_encode([
            'timeout' => true
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 1))->handleGetRequest('get_new_votes', []);
    }
    */
    
    public function testGetVotesOfPollNoVotesExist()
    {
        $expected = json_encode([
            'votes' => 0
        ]);
                
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 1))->handleGetRequest('get_votes');
    }
    
    public function testGetVotesOfPoll()
    {
        $expected = json_encode([
            'votes' => round((1/150)*100, 2), 
            'last' => 'Test', 
            'country' => 'United States'
        ]);
       
        (new Votes($this->database))->vote(1, 'Test', 'test2@example.com', 'United States');
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 1))->handleGetRequest('get_votes');
    }
    
    public function testAddNewVoteMissingAllInputsFails()
    {
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'name-error',
            'message' => 'Please Complete Name'
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->database, 1))->handlePostRequest(null, []);
        
    }
    
    public function testAddNewVoteMissingOneInputFails()
    {
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'email-error',
            'message' => 'Please Complete Email'
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->database, 1))->handlePostRequest(null, ['pid' => 1, 'name' => 'Test', 'country' => 'United States']);
        
    }
    
    public function testAddNewVoteSuccess()
    {
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'success' => true
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->database, 1))->handlePostRequest(null, ['pid' => 1, 'name' => 'Test', 'email' => 'test@example.com', 'country' => 'United States']);
    }
}